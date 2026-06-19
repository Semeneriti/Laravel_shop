<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // Очистить таблицы
        OrderItem::truncate();
        Order::truncate();

        $users = User::all();

        foreach ($users as $user) {
            // Создать 1-2 заказа для каждого пользователя
            for ($i = 0; $i < 2; $i++) {
                $total = 0;
                $products = Product::inRandomOrder()->limit(3)->get();

                $order = Order::create([
                    'user_id' => $user->id,
                    'total' => 0,
                    'status' => ['pending', 'paid', 'shipped', 'completed'][rand(0, 3)],
                    'shipping_address' => "г. Москва, ул. Тестовая, д. " . ($i + 1),
                ]);

                foreach ($products as $product) {
                    $quantity = rand(1, 2);
                    $price = $product->price;
                    $total += $price * $quantity;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'price' => $price,
                    ]);
                }

                $order->update(['total' => $total]);
            }
        }
    }
}
