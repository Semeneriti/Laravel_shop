<?php

declare(strict_types=1);

namespace App\DTO;

use App\Http\Requests\CartRequest;

class CartDto
{
    public function __construct(
        public readonly int ,
    ) {}

    public static function fromRequest(CartRequest ): self
    {
        return new self(
            quantity: (int) (->validated('quantity') ?? 1),
        );
    }
}
