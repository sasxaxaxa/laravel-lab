<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Тестовый Пользователь',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'name' => 'Модератор',
            'email' => 'moderator@example.com',
            'password' => Hash::make('password123'),
            'role_id' => 'moderator',
        ]);

        User::factory()->count(5)->create();
    }
}