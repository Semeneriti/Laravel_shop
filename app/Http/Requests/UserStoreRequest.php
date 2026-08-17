<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'status' => ['required', Rule::in(User::STATUSES)],
            'role_id' => ['required', 'exists:roles,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'Имя обязательно',
            'last_name.required' => 'Фамилия обязательна',
            'email.required' => 'Email обязателен',
            'email.unique' => 'Пользователь с таким email уже существует',
            'status.required' => 'Статус обязателен',
            'status.in' => 'Некорректный статус',
            'role_id.required' => 'Роль обязательна',
            'role_id.exists' => 'Выбранная роль не существует',
        ];
    }
}
