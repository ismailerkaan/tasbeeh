<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreZikirRequest extends FormRequest
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
            'zikir_category_id' => ['required', 'integer', 'exists:zikir_categories,id'],
            'zikir' => ['required', 'string', 'max:255'],
            'anlami' => ['required', 'string'],
            'fazileti' => ['required', 'string'],
            'hedef' => ['required', 'integer', 'min:1'],
        ];
    }
}
