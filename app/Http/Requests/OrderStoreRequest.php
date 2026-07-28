<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payment_method' => ['required', 'in:cash,card'],
        ];
    }

    public function messages(): array
    {
        return [
            'payment_method.required' => 'Выберите способ оплаты',
            'payment_method.in' => 'Некорректный способ оплаты',
        ];
    }
}
