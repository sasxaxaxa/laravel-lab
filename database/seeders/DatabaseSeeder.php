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
        $this->call([
            UserSeeder::class,
        ]);

        Article::factory()->count(50)->create();
    }
}