<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreTestRunRequest;
use App\Models\MobileUser;
use App\Models\MobileUserTestAnswer;
use App\Models\MobileUserTestRun;
use App\Models\MobileUserTestStat;
use App\Models\TestCategory;
use App\Models\TestLevel;
use App\Models\TestQuestion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function categories(): JsonResponse
    {
        $categories = TestCategory::query()
            ->where('is_active', true)
            ->withCount([
                'levels as level_count' => fn ($query) => $query->where('is_active', true),
                'questions as question_count' => fn ($query) => $query
                    ->where('test_questions.is_active', true)
                    ->whereHas('level', fn ($levelQuery) => $levelQuery->where('is_active', true)),
            ])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(fn (TestCategory $category) => $this->categoryPayload($category));

        $uncategorizedLevels = TestLevel::query()
            ->whereNull('test_category_id')
            ->where('is_active', true)
            ->withCount(['questions' => fn ($query) => $query->where('is_active', true)])
            ->get();

        if ($uncategorizedLevels->isNotEmpty()) {
            $categories->push([
                'id' => 'uncategorized',
                'title' => 'Genel Testler',
                'description' => 'Henüz kategoriye bağlanmamış testler.',
                'level_count' => $uncategorizedLevels->count(),
                'question_count' => $uncategorizedLevels->sum('questions_count'),
                'order' => 65535,
            ]);
        }

        return response()->json(['data' => $categories->values()]);
    }

    public function levels(Request $request): JsonResponse
    {
        $categoryId = $request->query('category_id');
        $levels = TestLevel::query()
            ->where('is_active', true)
            ->with('category')
            ->when($categoryId === 'uncategorized', fn ($query) => $query->whereNull('test_category_id'))
            ->when($categoryId !== null && $categoryId !== 'uncategorized' && ctype_digit((string) $categoryId), fn ($query) => $query->where('test_category_id', (int) $categoryId))
            ->withCount(['questions' => fn ($query) => $query->where('is_active', true)])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(fn (TestLevel $level) => [
                'id' => $level->id,
                'category_id' => $level->test_category_id,
                'category_title' => $level->category?->name,
                'title' => $level->name,
                'description' => $level->description,
                'question_count' => $level->questions_count,
                'order' => $level->sort_order,
            ]);

        return response()->json(['data' => $levels]);
    }

    public function stats(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'string', 'max:100'],
        ]);

        $mobileUser = MobileUser::query()
            ->where('external_user_id', $validated['user_id'])
            ->first();

        if (! $mobileUser) {
            return response()->json(['data' => $this->emptyStats()]);
        }

        $stats = MobileUserTestStat::query()
            ->where('mobile_user_id', $mobileUser->id)
            ->first();

        return response()->json(['data' => $this->statsPayload($stats)]);
    }

    public function storeRun(StoreTestRunRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $result = DB::transaction(function () use ($validated): array {
            /** @var MobileUser $mobileUser */
            $mobileUser = MobileUser::query()->firstOrCreate(
                ['external_user_id' => $validated['userId']],
                [
                    'is_opt_in' => true,
                    'total_zikir_count' => 0,
                    'synced_at' => now(),
                ]
            );

            /** @var MobileUserTestRun $run */
            $run = MobileUserTestRun::query()->create([
                'mobile_user_id' => $mobileUser->id,
                'test_level_id' => (int) $validated['levelId'],
                'score' => (int) $validated['score'],
                'correct_count' => (int) $validated['correctCount'],
                'total_questions' => (int) $validated['totalQuestions'],
                'best_streak' => (int) $validated['bestStreak'],
                'continued_with_ad' => (bool) $validated['continuedWithAd'],
                'ended_reason' => (string) $validated['endedReason'],
                'completed' => (bool) $validated['completed'],
                'started_at' => isset($validated['startedAt']) ? Carbon::parse((string) $validated['startedAt']) : now(),
                'ended_at' => isset($validated['endedAt']) ? Carbon::parse((string) $validated['endedAt']) : now(),
            ]);

            foreach ($validated['answers'] as $answer) {
                MobileUserTestAnswer::query()->create([
                    'mobile_user_test_run_id' => $run->id,
                    'test_question_id' => (int) $answer['questionId'],
                    'question_order' => (int) $answer['questionOrder'],
                    'selected_option_id' => (string) $answer['selectedOptionId'],
                    'correct_option_id' => $answer['correctOptionId'] ?? null,
                    'is_correct' => (bool) $answer['isCorrect'],
                    'score_earned' => (int) $answer['scoreEarned'],
                    'answered_at' => isset($answer['answeredAt']) ? Carbon::parse((string) $answer['answeredAt']) : now(),
                ]);
            }

            $stats = $this->updateStats($mobileUser, $validated);

            return [
                'run' => $run,
                'stats' => $stats,
            ];
        });

        return response()->json([
            'message' => 'Test run stored.',
            'data' => [
                'run_id' => $result['run']->id,
                'stats' => $this->statsPayload($result['stats']),
            ],
        ], 201);
    }

    public function questionsByLevel(TestLevel $level): JsonResponse
    {
        abort_unless($level->is_active, 404);

        $level->loadMissing('category');

        return response()->json([
            'data' => [
                'level' => [
                    'id' => $level->id,
                    'category_id' => $level->test_category_id,
                    'category_title' => $level->category?->name,
                    'title' => $level->name,
                    'description' => $level->description,
                ],
                'questions' => $this->questionsQuery($level->id)->get()->map(fn (TestQuestion $question) => $this->questionPayload($question)),
            ],
        ]);
    }

    public function questions(Request $request): JsonResponse
    {
        $levelId = $request->integer('level_id');
        abort_if($levelId <= 0, 422, 'level_id is required.');

        $level = TestLevel::query()
            ->where('is_active', true)
            ->findOrFail($levelId);

        return $this->questionsByLevel($level);
    }

    private function updateStats(MobileUser $mobileUser, array $validated): MobileUserTestStat
    {
        $stats = MobileUserTestStat::query()->firstOrNew(['mobile_user_id' => $mobileUser->id]);
        $levelBestScores = is_array($stats->level_best_scores) ? $stats->level_best_scores : [];
        $levelId = (string) $validated['levelId'];
        $score = (int) $validated['score'];

        $stats->fill([
            'total_score' => (int) ($stats->total_score ?? 0) + $score,
            'best_run_score' => max((int) ($stats->best_run_score ?? 0), $score),
            'completed_runs' => (int) ($stats->completed_runs ?? 0) + ((bool) $validated['completed'] ? 1 : 0),
            'answered_questions' => (int) ($stats->answered_questions ?? 0) + count($validated['answers']),
            'level_best_scores' => [
                ...$levelBestScores,
                $levelId => max((int) ($levelBestScores[$levelId] ?? 0), $score),
            ],
        ]);
        $stats->save();

        return $stats;
    }

    private function questionsQuery(int $levelId)
    {
        return TestQuestion::query()
            ->where('test_level_id', $levelId)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    /**
     * @return array<string, mixed>
     */
    private function categoryPayload(TestCategory $category): array
    {
        return [
            'id' => $category->id,
            'title' => $category->name,
            'description' => $category->description,
            'level_count' => (int) $category->level_count,
            'question_count' => (int) $category->question_count,
            'order' => $category->sort_order,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function questionPayload(TestQuestion $question): array
    {
        $optionKeys = ['A', 'B', 'C', 'D', 'E'];
        $options = collect($question->options ?? [])
            ->values()
            ->map(fn (string $text, int $index) => [
                'id' => $optionKeys[$index] ?? (string) ($index + 1),
                'text' => $text,
            ])
            ->all();

        return [
            'id' => $question->id,
            'question' => $question->question,
            'options' => $options,
            'correct_option_id' => $question->correct_option_key,
            'explanation' => $question->explanation,
            'order' => $question->sort_order,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function statsPayload(?MobileUserTestStat $stats): array
    {
        if (! $stats) {
            return $this->emptyStats();
        }

        return [
            'total_score' => (int) $stats->total_score,
            'best_run_score' => (int) $stats->best_run_score,
            'completed_runs' => (int) $stats->completed_runs,
            'answered_questions' => (int) $stats->answered_questions,
            'level_best_scores' => $stats->level_best_scores ?? [],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function emptyStats(): array
    {
        return [
            'total_score' => 0,
            'best_run_score' => 0,
            'completed_runs' => 0,
            'answered_questions' => 0,
            'level_best_scores' => [],
        ];
    }
}