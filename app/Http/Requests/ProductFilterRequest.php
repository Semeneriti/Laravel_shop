<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:255'],
            'min_price' => ['nullable', 'integer', 'min:0'],
            'max_price' => ['nullable', 'integer', 'min:0'],
            'in_stock' => ['nullable'],
            'sort' => ['nullable', 'in:new,price_asc,price_desc,name_asc,name_desc,stock_asc,stock_desc'],
            'per_page' => ['nullable', 'in:10,25,50,100'],
        ];
    }
}
