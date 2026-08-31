<?php

namespace App\Console\Commands;

use App\Models\TestCategory;
use App\Models\TestLevel;
use App\Models\TestQuestion;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use RuntimeException;

#[Signature('tests:import {path : İçe aktarılacak question.json dosyasının yolu} {--fresh : Mevcut test kategori, seviye ve sorularını temizleyip yeniden yükler}')]
#[Description('question.json dosyasındaki test kategori, seviye ve sorularını veritabanına aktarır.')]
class ImportTestsCommand extends Command
{
    private int $categoryCount = 0;
    private int $levelCount = 0;
    private int $questionCount = 0;
    private int $skippedQuestionCount = 0;

    public function handle(): int
    {
        $path = (string) $this->argument('path');

        if (! file_exists($path)) {
            $this->error("JSON dosyası bulunamadı: {$path}");

            return self::FAILURE;
        }

        try {
            $payload = $this->decodeJsonFile($path);
        } catch (RuntimeException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        DB::transaction(function () use ($payload): void {
            if ((bool) $this->option('fresh')) {
                $this->freshTestContent();
            }

            $this->importCategories($payload['test_categories']);
        });

        $this->info('Test içe aktarma tamamlandı.');
        $this->line('Kategori: '.$this->categoryCount);
        $this->line('Seviye: '.$this->levelCount);
        $this->line('Soru: '.$this->questionCount);

        if ($this->skippedQuestionCount > 0) {
            $this->warn('Atlanan soru: '.$this->skippedQuestionCount);
        }

        return self::SUCCESS;
    }

    private function freshTestContent(): void
    {
        TestQuestion::query()->delete();
        TestLevel::query()->delete();
        TestCategory::query()->delete();
        $this->info('Mevcut test kategori, seviye ve soruları silindi.');
    }

    /**
     * @param  array<int, array<string, mixed>>  $categories
     */
    private function importCategories(array $categories): void
    {
        foreach ($categories as $categoryIndex => $categoryItem) {
            $categoryName = trim((string) ($categoryItem['name'] ?? $categoryItem['title'] ?? ''));

            if ($categoryName === '') {
                continue;
            }

            /** @var TestCategory $category */
            $category = TestCategory::query()->updateOrCreate(
                ['name' => $categoryName],
                [
                    'description' => $this->nullableString($categoryItem['description'] ?? null),
                    'sort_order' => (int) ($categoryItem['sort_order'] ?? $categoryItem['order'] ?? $categoryIndex + 1),
                    'is_active' => $this->boolValue($categoryItem['is_active'] ?? true),
                ]
            );
            $this->categoryCount++;

            $levels = $categoryItem['levels'] ?? [];
            if (! is_array($levels)) {
                continue;
            }

            $this->importLevels($category, $levels);
        }
    }

    /**
     * @param  array<int, array<string, mixed>>  $levels
     */
    private function importLevels(TestCategory $category, array $levels): void
    {
        foreach ($levels as $levelIndex => $levelItem) {
            if (! is_array($levelItem)) {
                continue;
            }

            $levelName = trim((string) ($levelItem['name'] ?? $levelItem['title'] ?? ''));

            if ($levelName === '') {
                continue;
            }

            /** @var TestLevel $level */
            $level = TestLevel::query()->updateOrCreate(
                [
                    'test_category_id' => $category->id,
                    'name' => $levelName,
                ],
                [
                    'description' => $this->nullableString($levelItem['description'] ?? null),
                    'sort_order' => (int) ($levelItem['sort_order'] ?? $levelItem['order'] ?? $levelIndex + 1),
                    'is_active' => $this->boolValue($levelItem['is_active'] ?? true),
                ]
            );
            $this->levelCount++;

            $questions = $levelItem['questions'] ?? [];
            if (! is_array($questions)) {
                continue;
            }

            $this->importQuestions($level, $questions);
        }
    }

    /**
     * @param  array<int, array<string, mixed>>  $questions
     */
    private function importQuestions(TestLevel $level, array $questions): void
    {
        foreach ($questions as $questionIndex => $questionItem) {
            if (! is_array($questionItem) || ! $this->isValidQuestion($questionItem)) {
                $this->skippedQuestionCount++;
                continue;
            }

            $questionText = trim((string) $questionItem['question']);

            TestQuestion::query()->updateOrCreate(
                [
                    'test_level_id' => $level->id,
                    'question' => $questionText,
                ],
                [
                    'options' => array_values(array_map('strval', $questionItem['options'])),
                    'correct_option_key' => strtoupper((string) $questionItem['correct_option_key']),
                    'explanation' => $this->nullableString($questionItem['explanation'] ?? null),
                    'sort_order' => (int) ($questionItem['sort_order'] ?? $questionItem['order'] ?? $questionIndex + 1),
                    'is_active' => $this->boolValue($questionItem['is_active'] ?? true),
                ]
            );
            $this->questionCount++;
        }
    }

    /**
     * @param  array<string, mixed>  $question
     */
    private function isValidQuestion(array $question): bool
    {
        $text = trim((string) ($question['question'] ?? ''));
        $options = $question['options'] ?? [];
        $correctKey = strtoupper((string) ($question['correct_option_key'] ?? ''));
        $allowedKeys = array_slice(['A', 'B', 'C', 'D', 'E'], 0, is_array($options) ? count($options) : 0);

        return $text !== ''
            && is_array($options)
            && count($options) >= 2
            && count($options) <= 5
            && collect($options)->every(fn ($option) => trim((string) $option) !== '')
            && in_array($correctKey, $allowedKeys, true);
    }

    /**
     * @return array{test_categories: array<int, array<string, mixed>>}
     */
    private function decodeJsonFile(string $path): array
    {
        $rawContent = file_get_contents($path);

        if ($rawContent === false || trim($rawContent) === '') {
            throw new RuntimeException("Dosya boş veya okunamadı: {$path}");
        }

        /** @var mixed $decoded */
        $decoded = json_decode($rawContent, true);

        if (! is_array($decoded) || ! isset($decoded['test_categories']) || ! is_array($decoded['test_categories'])) {
            throw new RuntimeException('JSON içinde test_categories dizisi bulunamadı.');
        }

        return $decoded;
    }

    private function nullableString(mixed $value): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    private function boolValue(mixed $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? (bool) $value;
    }
}