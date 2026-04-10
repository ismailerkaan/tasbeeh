<?php

namespace App\Http\Requests\Admin;

use App\Models\PushNotification;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePushNotificationRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'target_type' => ['required', 'string', Rule::in(PushNotification::TARGET_TYPES)],
            'target_user_identifier' => [
                Rule::requiredIf(fn () => $this->input('target_type') === PushNotification::TARGET_USER),
                'nullable',
                'string',
                'max:255',
            ],
            'data' => ['nullable', 'string'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function payloadData(): array
    {
        $rawData = $this->input('data');

        if ($rawData === null || trim((string) $rawData) === '') {
            return [];
        }

        $decoded = json_decode((string) $rawData, true);

        if (! is_array($decoded)) {
            return [];
        }

        return $decoded;
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            $rawData = $this->input('data');

            if ($rawData === null || trim((string) $rawData) === '') {
                return;
            }

            json_decode((string) $rawData, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $validator->errors()->add('data', 'Data alanı geçerli bir JSON olmalıdır.');
            }
        });
    }

    /**
     * @return array<string, mixed>
     */
    public function normalizedPayload(): array
    {
        $validated = $this->validated();

        return [
            'title' => $validated['title'],
            'body' => $validated['body'],
            'target_type' => $validated['target_type'],
            'target_user_identifier' => $validated['target_user_identifier'] ?? null,
            'data' => $this->payloadData(),
        ];
    }
}
