<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Order;
use Tests\TestCase;

class OrderStatusTest extends TestCase
{
    public function test_status_labels_contain_all_statuses(): void
    {
        $this->assertArrayHasKey(Order::STATUS_PENDING, Order::STATUS_LABELS);
        $this->assertArrayHasKey(Order::STATUS_PAID, Order::STATUS_LABELS);
        $this->assertArrayHasKey(Order::STATUS_CANCELED, Order::STATUS_LABELS);
    }

    public function test_payment_methods_contain_yookassa(): void
    {
        $this->assertContains(Order::PAYMENT_METHOD_YOOKASSA, Order::PAYMENT_METHODS);
        $this->assertContains(Order::PAYMENT_METHOD_CASH, Order::PAYMENT_METHODS);
    }
}
