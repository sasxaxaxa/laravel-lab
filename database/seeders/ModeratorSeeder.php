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

        $moderator = User::create([
            'name' => 'Администратор Модератор',
            'email' => 'moderator@example.com',
            'password' => Hash::make('moderator123'),
            'role_id' => $moderatorRole->id,
        ]);

        $readerRole = Role::where('name', 'reader')->first();
        
        User::create([
            'name' => 'Обычный Читатель',
            'email' => 'reader@example.com',
            'password' => Hash::make('reader123'),
            'role_id' => $readerRole->id,
        ]);

        $users = User::whereNull('role_id')->take(3)->get();
        foreach ($users as $user) {
            $user->update(['role_id' => $readerRole->id]);
        }
    }
}