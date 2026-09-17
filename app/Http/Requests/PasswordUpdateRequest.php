<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PasswordUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'current_password' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'password.confirmed' => 'Подтверждение пароля не совпадает.',
            'password.min' => 'Пароль должен содержать не менее 8 символов.',
            'current_password.required' => 'Введите текущий пароль',
        ];
    }
}
