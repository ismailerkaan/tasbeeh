<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTestQuestionRequest extends FormRequest
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
            'test_level_id' => ['required', 'integer', 'exists:test_levels,id'],
            'question' => ['required', 'string'],
            'options' => ['required', 'array', 'min:2', 'max:5'],
            'options.*' => ['required', 'string', 'max:1000'],
            'correct_option_key' => ['required', Rule::in(array_keys($this->optionKeys()))],
            'explanation' => ['nullable', 'string'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:65535'],
            'is_active' => ['required', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function optionKeys(): array
    {
        return ['A' => 'A', 'B' => 'B', 'C' => 'C', 'D' => 'D', 'E' => 'E'];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            $keys = array_keys($this->optionKeys());
            $selectedIndex = array_search($this->input('correct_option_key'), $keys, true);

            if ($selectedIndex === false || ! array_key_exists($selectedIndex, $this->input('options', []))) {
                $validator->errors()->add('correct_option_key', 'Doğru şık girilen seçeneklerden biri olmalıdır.');
            }
        });
    }

    protected function prepareForValidation(): void
    {
        $options = collect($this->input('options', []))
            ->map(fn ($value) => is_string($value) ? trim($value) : $value)
            ->filter(fn ($value) => is_string($value) && $value !== '')
            ->values()
            ->all();

        $this->merge([
            'options' => $options,
            'correct_option_key' => strtoupper((string) $this->input('correct_option_key')),
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}