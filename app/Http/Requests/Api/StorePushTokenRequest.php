<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePushTokenRequest extends FormRequest
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
            'token' => ['required', 'string', 'max:255'],
            'user_identifier' => ['nullable', 'string', 'max:255'],
            'platform' => ['nullable', 'string', 'in:android,ios'],
        ];
    }
}
