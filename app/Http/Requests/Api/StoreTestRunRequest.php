<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTestRunRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<int, ValidationRule|string>|string>
     */
    public function rules(): array
    {
        return [
            'userId' => ['required', 'string', 'max:100'],
            'levelId' => ['required', 'integer', 'exists:test_levels,id'],
            'score' => ['required', 'integer', 'min:0'],
            'correctCount' => ['required', 'integer', 'min:0'],
            'totalQuestions' => ['required', 'integer', 'min:0'],
            'bestStreak' => ['required', 'integer', 'min:0'],
            'continuedWithAd' => ['required', 'boolean'],
            'endedReason' => ['required', 'string', Rule::in(['completed', 'wrong_restart', 'abandoned'])],
            'completed' => ['required', 'boolean'],
            'startedAt' => ['nullable', 'date'],
            'endedAt' => ['nullable', 'date'],
            'answers' => ['required', 'array', 'min:1'],
            'answers.*.questionId' => ['required', 'integer', 'exists:test_questions,id'],
            'answers.*.questionOrder' => ['required', 'integer', 'min:1', 'max:65535'],
            'answers.*.selectedOptionId' => ['required', 'string', 'max:20'],
            'answers.*.correctOptionId' => ['nullable', 'string', 'max:20'],
            'answers.*.isCorrect' => ['required', 'boolean'],
            'answers.*.scoreEarned' => ['required', 'integer', 'min:0'],
            'answers.*.answeredAt' => ['nullable', 'date'],
        ];
    }
}