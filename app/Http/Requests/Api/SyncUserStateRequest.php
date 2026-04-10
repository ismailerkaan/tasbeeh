<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SyncUserStateRequest extends FormRequest
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
            'fcmToken' => ['required', 'string', 'max:255'],

            'device' => ['required', 'array'],
            'device.name' => ['nullable', 'string', 'max:255'],
            'device.model' => ['nullable', 'string', 'max:255'],
            'device.os' => ['nullable', 'string', 'max:20'],
            'device.version' => ['nullable', 'string', 'max:50'],

            'location' => ['nullable', 'array'],
            'location.city' => ['nullable', 'string', 'max:100'],
            'location.district' => ['nullable', 'string', 'max:100'],

            'lastZikir' => ['nullable', 'array'],
            'lastZikir.id' => ['required_with:lastZikir', 'string', 'max:100'],
            'lastZikir.name' => ['required_with:lastZikir', 'string', 'max:255'],
            'lastZikir.count' => ['required_with:lastZikir', 'integer', 'min:0'],

            'readZikirs' => ['nullable', 'array'],
            'readZikirs.*' => ['string', 'max:100'],
            'zikirCounts' => ['nullable', 'array'],
            'zikirCounts.*' => ['integer', 'min:0'],
            'readDuas' => ['nullable', 'array'],
            'readDuas.*' => ['string', 'max:100'],

            'isOptIn' => ['required', 'boolean'],
            'totalZikirCount' => ['required', 'integer', 'min:0'],
            'streak' => ['nullable', 'array'],
            'streak.current' => ['nullable', 'integer', 'min:0'],
            'streak.best' => ['nullable', 'integer', 'min:0'],
            'streak.totalActiveDays' => ['nullable', 'integer', 'min:0'],
            'streak.lastActiveDate' => ['nullable', 'date_format:Y-m-d'],
            'dailyActivitySummary' => ['nullable', 'array'],
            'dailyActivitySummary.*.date' => ['required_with:dailyActivitySummary', 'date_format:Y-m-d'],
            'dailyActivitySummary.*.totalCount' => ['nullable', 'integer', 'min:0'],
            'dailyActivitySummary.*.completedDailyZikr' => ['nullable', 'boolean'],
            'zikirVersion' => ['nullable', 'integer', 'min:1'],
            'duaVersion' => ['nullable', 'integer', 'min:1'],
            'prayerTimesVersion' => ['nullable', 'integer', 'min:1'],
            'updatedAt' => ['required', 'date'],
        ];
    }
}
