<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Очистить таблицу
        User::truncate();

        User::create([
            'first_name' => 'Иван',
            'last_name' => 'Петров',
            'email' => 'ivan@example.com',
            'phone' => '+79001234567',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        User::create([
            'first_name' => 'Мария',
            'last_name' => 'Иванова',
            'email' => 'maria@example.com',
            'phone' => '+79007654321',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        User::create([
            'first_name' => 'Петр',
            'last_name' => 'Сидоров',
            'email' => 'petr@example.com',
            'phone' => '+79001112233',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
    }
}
