<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => [
                'required',
                Rule::in([Order::STATUS_PAID, Order::STATUS_CANCELED]),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Укажите статус',
            'status.in' => 'Некорректный статус',
        ];
    }
}
