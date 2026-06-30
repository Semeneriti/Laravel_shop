<?php

declare(strict_types=1);

namespace App\DTO;

use App\Http\Requests\RegisterRequest;

class RegisterDto
{
    public function __construct(
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly string $email,
        public readonly ?string $phone,
        public readonly string $password,
    ) {
    }

    /**
     * Создать DTO из Request
     */
    public static function fromRequest(RegisterRequest $request): self
    {
        $validated = $request->validated();

        return new self(
            firstName: $validated['first_name'],
            lastName: $validated['last_name'],
            email: $validated['email'],
            phone: $validated['phone'] ?? null,
            password: $validated['password'],
        );
    }
}
