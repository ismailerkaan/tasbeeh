<?php

use App\Models\TestLevel;
use App\Models\TestQuestion;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('test levels endpoint returns active levels with question counts', function () {
    $activeLevel = TestLevel::query()->create([
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

    $response = $this->getJson(route('api.v1.tests.levels'));

    $response
        ->assertOk()
        ->assertJsonPath('data.0.id', $activeLevel->id)
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