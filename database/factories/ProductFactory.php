<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->randomFloat(2, 100, 10000),
            'image' => null,
            'stock' => $this->faker->numberBetween(1, 100),
            'sku' => $this->faker->unique()->ean8(),
            'status' => 'active',
            'category_id' => null,
        ];
    }
}
