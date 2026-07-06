<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreMobileUserPushNotificationRequest extends FormRequest
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
            'data' => ['nullable', 'string'],
        ];
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
    public function payloadData(): array
    {
        $rawData = $this->input('data');

        if ($rawData === null || trim((string) $rawData) === '') {
            return [];
        }

        $decoded = json_decode((string) $rawData, true);

        return is_array($decoded) ? $decoded : [];
    }
}
