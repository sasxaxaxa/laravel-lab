<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ModeratorSeeder extends Seeder
{
   public function run(): void
{
    $moderatorRole = Role::where('name', 'moderator')->first();
    $readerRole = Role::where('name', 'reader')->first();

    // Используем firstOrCreate для избежания дубликатов
    $moderator = User::firstOrCreate(
        ['email' => 'moderator@example.com'],
        [
            'name' => 'Администратор Модератор',
            'password' => Hash::make('moderator123'),
            'role_id' => $moderatorRole->id,
        ]
    );

    $reader = User::firstOrCreate(
        ['email' => 'reader@example.com'],
        [
            'name' => 'Обычный Читатель',
            'password' => Hash::make('reader123'),
            'role_id' => $readerRole->id,
        ]
    );

    // Назначить роль читателя пользователям без роли
    $usersWithoutRole = User::whereNull('role_id')->take(3)->get();
    foreach ($usersWithoutRole as $user) {
        $user->update(['role_id' => $readerRole->id]);
    }
}
}