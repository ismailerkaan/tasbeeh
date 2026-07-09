<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TestLevel;
use App\Models\TestQuestion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function levels(): JsonResponse
    {
        $levels = TestLevel::query()
            ->where('is_active', true)
            ->withCount(['questions' => fn ($query) => $query->where('is_active', true)])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(fn (TestLevel $level) => [
                'id' => $level->id,
                'title' => $level->name,
                'description' => $level->description,
                'question_count' => $level->questions_count,
                'order' => $level->sort_order,
            ]);

        return response()->json(['data' => $levels]);
    }

    public function questionsByLevel(TestLevel $level): JsonResponse
    {
        abort_unless($level->is_active, 404);

        return response()->json([
            'data' => [
                'level' => [
                    'id' => $level->id,
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
}