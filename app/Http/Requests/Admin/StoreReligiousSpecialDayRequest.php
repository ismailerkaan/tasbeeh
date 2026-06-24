<?php

namespace App\Http\Requests\Admin;

use App\Models\ReligiousSpecialDay;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReligiousSpecialDayRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', Rule::in(array_keys(ReligiousSpecialDay::CATEGORIES))],
            'event_date' => ['required', 'date_format:Y-m-d'],
            'hijri_date' => ['nullable', 'string', 'max:100'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['required', 'string'],
            'recommendations_text' => ['required', 'string'],
            'is_active' => ['required', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}
