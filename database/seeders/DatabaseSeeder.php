<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Создаем 50 статей
        Article::factory()->count(50)->create();
        
        // Создаем несколько специфических статей
        Article::factory()->count(5)->unpublished()->create();
        Article::factory()->count(10)->category('technology')->create();
        Article::factory()->count(5)->popular()->create();
        
        $this->call([
            // Здесь можно добавить другие сидеры
        ]);
    }
}