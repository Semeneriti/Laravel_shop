<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    public function run(): void
    {
        // Получить пользователей
        $users = User::all();

        // Для каждого пользователя создать корзину
        foreach ($users as $user) {
            $cart = Cart::create([
                'user_id' => $user->id,
                'status' => 'active',
            ]);

            // Добавить 2-3 товара в корзину
            $products = Product::inRandomOrder()->limit(3)->get();
            foreach ($products as $product) {
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'quantity' => rand(1, 3),
                    'price' => $product->price,
                ]);
            }
        }
    }
}
