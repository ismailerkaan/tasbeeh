<?php

namespace App\Http\Requests\Admin;

use App\Models\MobileUser;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMobileUserRequest extends FormRequest
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
        /** @var MobileUser|null $mobileUser */
        $mobileUser = $this->route('mobile_user');

        return [
            'external_user_id' => [
                'required',
                'string',
                'max:255',
                Rule::unique('mobile_users', 'external_user_id')->ignore($mobileUser?->id),
            ],
            'city' => ['nullable', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:255'],
            'is_opt_in' => ['required', 'boolean'],
            'total_zikir_count' => ['required', 'integer', 'min:0'],
            'synced_at' => ['nullable', 'date'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_opt_in' => $this->boolean('is_opt_in'),
            'total_zikir_count' => (int) ($this->input('total_zikir_count') ?: 0),
            'synced_at' => $this->input('synced_at') ?: null,
        ]);
    }
}
