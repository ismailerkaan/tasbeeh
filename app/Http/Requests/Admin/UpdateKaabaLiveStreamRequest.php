<?php

namespace App\Http\Requests\Admin;

use Closure;
use Illuminate\Foundation\Http\FormRequest;

class UpdateKaabaLiveStreamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:100'],
            'youtube_url' => [
                'required',
                'url:http,https',
                'max:2048',
                function (string $attribute, mixed $value, Closure $fail): void {
                    $host = strtolower((string) parse_url((string) $value, PHP_URL_HOST));
                    if (! in_array($host, ['youtube.com', 'www.youtube.com', 'm.youtube.com', 'youtu.be'], true)) {
                        $fail('Geçerli bir YouTube bağlantısı girin.');
                    }
                },
            ],
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
