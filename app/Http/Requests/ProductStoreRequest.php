<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'sku' => ['required', 'string', 'max:50', 'unique:products,sku'],
            'status' => ['required', Rule::in([Product::STATUS_ACTIVE, Product::STATUS_INACTIVE])],
            'image' => ['nullable', 'image', 'max:2048'],
            'category_id' => ['nullable', 'exists:categories,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Название товара обязательно',
            'price.required' => 'Цена обязательна',
            'price.numeric' => 'Цена должна быть числом',
            'stock.required' => 'Количество на складе обязательно',
            'stock.integer' => 'Количество должно быть целым числом',
            'sku.required' => 'Артикул обязателен',
            'sku.unique' => 'Товар с таким артикулом уже существует',
            'status.required' => 'Статус обязателен',
            'status.in' => 'Некорректный статус',
            'image.image' => 'Файл должен быть изображением',
            'image.max' => 'Размер изображения не должен превышать 2 МБ',
            'category_id.exists' => 'Выбранная категория не существует',
        ];
    }
}
