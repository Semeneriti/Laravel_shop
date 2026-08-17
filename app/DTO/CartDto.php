<?php

declare(strict_types=1);

namespace App\DTO;

use App\Http\Requests\CartRequest;

class CartDto
{
    public function __construct(
        public readonly int $quantity,
    ) {
    }

    public static function fromRequest(CartRequest $request): self
    {
        return new self(
            quantity: (int) ($request->validated('quantity') ?? 1),
        );
    }
}
