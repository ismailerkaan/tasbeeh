<?php

namespace App\Http\Requests\Admin;

use App\Models\DailyZikr;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDailyZikrRequest extends FormRequest
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
        /** @var DailyZikr|null $dailyZikr */
        $dailyZikr = $this->route('daily_zikr');
        $locale = $this->input('locale');

        return [
            'date' => [
                'required',
                'date_format:Y-m-d',
                Rule::unique('daily_zikrs', 'date')
                    ->ignore($dailyZikr?->id)
                    ->where(function ($query) use ($locale): void {
                        if ($locale === null || $locale === '') {
                            $query->whereNull('locale');
                        } else {
                            $query->where('locale', $locale);
                        }
                    }),
            ],
            'locale' => ['nullable', 'string', 'max:10'],
            'zikir_id' => ['required', 'integer', 'exists:zikirs,id'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
