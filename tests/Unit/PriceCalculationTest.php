<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class PriceCalculationTest extends TestCase
{
    public function test_price_multiplied_by_quantity(): void
    {
        $price = 1000;
        $quantity = 3;

        $total = $price * $quantity;

        $this->assertEquals(3000, $total);
    }

    public function test_price_with_discount(): void
    {
        $price = 1000;
        $discount = 10;

        $total = $price - ($price * $discount / 100);

        $this->assertEquals(900, $total);
    }
}
