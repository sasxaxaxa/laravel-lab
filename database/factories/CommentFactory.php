<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'content' => $this->faker->paragraph(2),
            'user_id' => User::inRandomOrder()->first()->id,
            'article_id' => Article::inRandomOrder()->first()->id,
            'is_approved' => $this->faker->boolean(80),
            'created_at' => $this->faker->dateTimeBetween('-3 months', 'now'),
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_approved' => true,
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_approved' => false,
        ]);
    }
}