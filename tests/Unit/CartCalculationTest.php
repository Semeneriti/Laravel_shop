<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class CartCalculationTest extends TestCase
{
    public function test_total_quantity_sum(): void
    {
        $items = [1 => 2, 2 => 3, 3 => 1];

        $total = array_sum($items);

        $this->assertEquals(6, $total);
    }

    public function test_empty_cart_returns_zero(): void
    {
        $items = [];

        $total = array_sum($items);

        $this->assertEquals(0, $total);
    }
}
