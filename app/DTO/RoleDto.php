<?php

declare(strict_types=1);

namespace App\DTO;

class RoleDto
{
    public function __construct(
        public readonly string $name,
        public readonly string $slug,
    ) {
    }
}
