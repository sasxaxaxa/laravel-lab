<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'moderator',
                'display_name' => 'Модератор',
                'description' => 'Может управлять статьями и комментариями',
            ],
            [
                'name' => 'reader',
                'display_name' => 'Читатель',
                'description' => 'Может читать статьи и оставлять комментарии',
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}