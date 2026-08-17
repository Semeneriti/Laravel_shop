<?php

declare(strict_types=1);

namespace App\DTO;

use Spatie\LaravelData\Data;

class ProductDto extends Data
{
    public function __construct(
        public readonly string $name,
        public readonly float $price,
        public readonly int $stock,
        public readonly string $sku,
        public readonly string $status,
        public readonly ?int $category_id,
        public readonly mixed $image,
    ) {
    }

    public function toProductData(): array
    {
        return [
            'name' => $this->name,
            'price' => $this->price,
            'stock' => $this->stock,
            'sku' => $this->sku,
            'status' => $this->status,
            'category_id' => $this->category_id,
        ];
    }

    public static function fromRequest($request): self
    {
        return new self(
            name: $request->validated('name'),
            price: (float) $request->validated('price'),
            stock: (int) $request->validated('stock'),
            sku: $request->validated('sku'),
            status: $request->validated('status'),
            category_id: $request->validated('category_id') ? (int) $request->validated('category_id') : null,
            image: $request->file('image'),
        );
    }
}
