<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminOrderUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'status' => ['required', Rule::in(array_keys(Order::STATUS_LABELS))],
            'payment_method' => ['required', Rule::in(['cash', 'card'])],
            'shipping_address' => ['nullable', 'string', 'max:500'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.price' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'Выберите пользователя',
            'user_id.exists' => 'Пользователь не найден',
            'status.required' => 'Выберите статус',
            'items.required' => 'Добавьте хотя бы один товар',
            'items.min' => 'Добавьте хотя бы один товар',
            'items.*.product_id.required' => 'Выберите товар',
            'items.*.product_id.exists' => 'Товар не найден',
            'items.*.quantity.min' => 'Количество должно быть не менее 1',
            'items.*.price.min' => 'Цена должна быть не менее 0',
        ];
    }
}
