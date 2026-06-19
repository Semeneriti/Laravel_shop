<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::create([
            'name' => 'iPhone 16 Pro',
            'description' => 'Флагманский смартфон Apple с мощным процессором и отличной камерой',
            'price' => 120000,
            'image' => 'https://via.placeholder.com/300x300?text=iPhone+16+Pro',
        ]);

        Product::create([
            'name' => 'MacBook Pro 16"',
            'description' => 'Мощный ноутбук для разработчиков и дизайнеров',
            'price' => 250000,
            'image' => 'https://via.placeholder.com/300x300?text=MacBook+Pro',
        ]);

        Product::create([
            'name' => 'Samsung Galaxy S24 Ultra',
            'description' => 'Флагман Samsung с S Pen и лучшей камерой на рынке',
            'price' => 110000,
            'image' => 'https://via.placeholder.com/300x300?text=Samsung+S24',
        ]);

        Product::create([
            'name' => 'AirPods Max',
            'description' => 'Беспроводные наушники премиум-класса с шумоподавлением',
            'price' => 45000,
            'image' => 'https://via.placeholder.com/300x300?text=AirPods+Max',
        ]);

        Product::create([
            'name' => 'iPad Pro 12.9"',
            'description' => 'Планшет для работы и творчества с чипом M2',
            'price' => 95000,
            'image' => 'https://via.placeholder.com/300x300?text=iPad+Pro',
        ]);
    }
}
