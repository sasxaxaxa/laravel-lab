<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $title = $this->faker->sentence(6);
        $categories = ['politics', 'sports', 'technology', 'entertainment', 'business', 'health'];
        
        $images = [
            'news1.jpg',
            'news2.jpg',
            'news3.jpg',
            'news4.jpg',
            'news5.jpg'
        ];
        
        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => $this->faker->paragraphs(5, true),
            'category' => $this->faker->randomElement($categories),
            'author' => $this->faker->name(),
            'image' => '/images/articles/' . $this->faker->randomElement($images),
            'views' => $this->faker->numberBetween(0, 10000),
            'is_published' => $this->faker->boolean(90),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}