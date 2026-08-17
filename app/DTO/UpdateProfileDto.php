<?php

declare(strict_types=1);

namespace App\DTO;

use App\Http\Requests\UpdateProfileRequest;

class UpdateProfileDto
{
    public function __construct(
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly string $email,
        public readonly ?string $phone,
    ) {
    }

    public static function fromRequest(UpdateProfileRequest $request): self
    {
        $validated = $request->validated();

        return new self(
            firstName: $validated['first_name'],
            lastName: $validated['last_name'],
            email: $validated['email'],
            phone: $validated['phone'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email' => $this->email,
            'phone' => $this->phone,
        ];
    }
}
