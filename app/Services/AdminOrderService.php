<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class AdminOrderService
{
    public function create(array $data): Order
    {
        return DB::transaction(function () use ($data): Order {
            $order = Order::create([
                'user_id' => $data['user_id'],
                'status' => $data['status'],
                'payment_method' => $data['payment_method'] ?? 'cash',
                'total' => 0,
                'shipping_address' => $data['shipping_address'] ?? '',
            ]);

            $total = 0;

            foreach ($data['items'] as $item) {
                $price = $item['price'] ?? 0;
                $quantity = $item['quantity'] ?? 1;
                $subtotal = $price * $quantity;
                $total += $subtotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $quantity,
                    'price' => $price,
                ]);
            }

            $order->update(['total' => $total]);

            return $order;
        });
    }

    public function update(Order $order, array $data): Order
    {
        return DB::transaction(function () use ($order, $data): Order {
            $order->update([
                'user_id' => $data['user_id'],
                'status' => $data['status'],
                'payment_method' => $data['payment_method'] ?? 'cash',
                'shipping_address' => $data['shipping_address'] ?? '',
            ]);

            $order->items()->delete();

            $total = 0;

            foreach ($data['items'] as $item) {
                $price = $item['price'] ?? 0;
                $quantity = $item['quantity'] ?? 1;
                $subtotal = $price * $quantity;
                $total += $subtotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $quantity,
                    'price' => $price,
                ]);
            }

            $order->update(['total' => $total]);

            return $order;
        });
    }

    public function delete(Order $order): void
    {
        DB::transaction(function () use ($order): void {
            $order->items()->delete();
            $order->delete();
        });
    }
}
