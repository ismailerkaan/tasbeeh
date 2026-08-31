<?php

use App\Models\MobileUser;
use App\Models\MobileUserTestAnswer;
use App\Models\MobileUserTestRun;
use App\Models\MobileUserTestStat;
use App\Models\TestCategory;
use App\Models\TestLevel;
use App\Models\TestQuestion;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('test categories endpoint returns active categories with level and question counts', function () {
    $category = TestCategory::query()->create([
        'name' => 'Namaz ile alakalı sorular',
        'description' => 'Namaz bilgisi testleri',
        'sort_order' => 1,
        'is_active' => true,
    ]);

    $level = TestLevel::query()->create([
        'test_category_id' => $category->id,
        'name' => 'Başlangıç',
        'is_active' => true,
    ]);

    TestQuestion::query()->create([
        'test_level_id' => $level->id,
        'question' => 'İlk soru?',
        'options' => ['A seçeneği', 'B seçeneği'],
        'correct_option_key' => 'A',
        'is_active' => true,
    ]);

    TestCategory::query()->create([
        'name' => 'Pasif kategori',
        'is_active' => false,
    ]);

    $response = $this->getJson(route('api.v1.tests.categories'));

    $response
        ->assertOk()
        ->assertJsonPath('data.0.id', $category->id)
        ->assertJsonPath('data.0.title', 'Namaz ile alakalı sorular')
        ->assertJsonPath('data.0.description', 'Namaz bilgisi testleri')
        ->assertJsonPath('data.0.level_count', 1)
        ->assertJsonPath('data.0.question_count', 1);

    expect($response->json('data'))->toHaveCount(1);
});

test('test levels endpoint returns active levels with question counts', function () {
    $category = TestCategory::query()->create([
        'name' => 'Namaz ile alakalı sorular',
        'is_active' => true,
    ]);

    $activeLevel = TestLevel::query()->create([
        'test_category_id' => $category->id,
        'name' => 'Başlangıç',
        'description' => 'Temel bilgiler',
        'sort_order' => 2,
        'is_active' => true,
    ]);

    TestQuestion::query()->create([
        'test_level_id' => $activeLevel->id,
        'question' => 'İlk soru?',
        'options' => ['A seçeneği', 'B seçeneği'],
        'correct_option_key' => 'A',
        'is_active' => true,
    ]);

    TestQuestion::query()->create([
        'test_level_id' => $activeLevel->id,
        'question' => 'Pasif soru?',
        'options' => ['A seçeneği', 'B seçeneği'],
        'correct_option_key' => 'B',
        'is_active' => false,
    ]);

    TestLevel::query()->create([
        'name' => 'Pasif seviye',
        'sort_order' => 1,
        'is_active' => false,
    ]);

    $response = $this->getJson(route('api.v1.tests.levels', ['category_id' => $category->id]));

    $response
        ->assertOk()
        ->assertJsonPath('data.0.id', $activeLevel->id)
        ->assertJsonPath('data.0.category_id', $category->id)
        ->assertJsonPath('data.0.category_title', 'Namaz ile alakalı sorular')
        ->assertJsonPath('data.0.title', 'Başlangıç')
        ->assertJsonPath('data.0.description', 'Temel bilgiler')
        ->assertJsonPath('data.0.question_count', 1);

    expect($response->json('data'))->toHaveCount(1);
});

test('test questions endpoint returns active questions mapped for mobile app', function () {
    $level = TestLevel::query()->create([
        'name' => 'Orta Seviye',
        'is_active' => true,
    ]);

    $question = TestQuestion::query()->create([
        'test_level_id' => $level->id,
        'question' => 'Namazın şartlarından biri hangisidir?',
        'options' => ['Niyet', 'Uyku', 'Yemek'],
        'correct_option_key' => 'A',
        'explanation' => 'Niyet ibadetin şartlarındandır.',
        'sort_order' => 1,
        'is_active' => true,
    ]);

    TestQuestion::query()->create([
        'test_level_id' => $level->id,
        'question' => 'Pasif soru',
        'options' => ['A', 'B'],
        'correct_option_key' => 'B',
        'is_active' => false,
    ]);

    $response = $this->getJson(route('api.v1.tests.levels.questions', $level));

    $response
        ->assertOk()
        ->assertJsonPath('data.level.id', $level->id)
        ->assertJsonPath('data.level.title', 'Orta Seviye')
        ->assertJsonPath('data.questions.0.id', $question->id)
        ->assertJsonPath('data.questions.0.question', 'Namazın şartlarından biri hangisidir?')
        ->assertJsonPath('data.questions.0.options.0.id', 'A')
        ->assertJsonPath('data.questions.0.options.0.text', 'Niyet')
        ->assertJsonPath('data.questions.0.correct_option_id', 'A')
        ->assertJsonPath('data.questions.0.explanation', 'Niyet ibadetin şartlarındandır.');

    expect($response->json('data.questions'))->toHaveCount(1);
});

test('mobile app can store test run answers and aggregate user score', function () {
    $level = TestLevel::query()->create([
        'name' => 'Başlangıç',
        'is_active' => true,
    ]);
    $question = TestQuestion::query()->create([
        'test_level_id' => $level->id,
        'question' => 'İlk soru?',
        'options' => ['Doğru', 'Yanlış'],
        'correct_option_key' => 'A',
        'is_active' => true,
    ]);

    $response = $this->postJson(route('api.v1.tests.runs.store'), [
        'userId' => 'u_test_123',
        'levelId' => $level->id,
        'score' => 10,
        'correctCount' => 1,
        'totalQuestions' => 1,
        'bestStreak' => 1,
        'continuedWithAd' => false,
        'endedReason' => 'completed',
        'completed' => true,
        'startedAt' => now()->subMinute()->toIso8601String(),
        'endedAt' => now()->toIso8601String(),
        'answers' => [
            [
                'questionId' => $question->id,
                'questionOrder' => 1,
                'selectedOptionId' => 'A',
                'correctOptionId' => 'A',
                'isCorrect' => true,
                'scoreEarned' => 10,
                'answeredAt' => now()->toIso8601String(),
            ],
        ],
    ]);

    $response
        ->assertCreated()
        ->assertJsonPath('data.stats.total_score', 10)
        ->assertJsonPath('data.stats.best_run_score', 10)
        ->assertJsonPath('data.stats.completed_runs', 1)
        ->assertJsonPath('data.stats.answered_questions', 1);

    $mobileUser = MobileUser::query()->where('external_user_id', 'u_test_123')->firstOrFail();

    expect(MobileUserTestRun::query()->where('mobile_user_id', $mobileUser->id)->count())->toBe(1);
    expect(MobileUserTestAnswer::query()->where('test_question_id', $question->id)->count())->toBe(1);
    expect(MobileUserTestStat::query()->where('mobile_user_id', $mobileUser->id)->value('total_score'))->toBe(10);
});

test('test stats endpoint returns stored aggregate score for mobile user', function () {
    $mobileUser = MobileUser::query()->create([
        'external_user_id' => 'u_score_123',
        'is_opt_in' => true,
        'total_zikir_count' => 0,
    ]);

    MobileUserTestStat::query()->create([
        'mobile_user_id' => $mobileUser->id,
        'total_score' => 40,
        'best_run_score' => 25,
        'completed_runs' => 2,
        'answered_questions' => 5,
        'level_best_scores' => ['1' => 25],
    ]);

    $this->getJson(route('api.v1.tests.stats', ['user_id' => 'u_score_123']))
        ->assertOk()
        ->assertJsonPath('data.total_score', 40)
        ->assertJsonPath('data.best_run_score', 25)
        ->assertJsonPath('data.completed_runs', 2)
        ->assertJsonPath('data.answered_questions', 5)
        ->assertJsonPath('data.level_best_scores.1', 25);
});