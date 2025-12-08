<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\Comment;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */


    public function run(): void
{
    $this->call([
        RoleSeeder::class,
        UserSeeder::class,
        ModeratorSeeder::class,
    ]);
    
    Article::factory()->count(50)->create();
    Comment::factory()->count(100)->create();
}
}