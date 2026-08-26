<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payment_method' => ['required', Rule::in(Order::PAYMENT_METHODS)],
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
