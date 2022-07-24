<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "title" => $this->faker->sentence($this->faker->numberBetween(8,17)),
            "description" => $this->faker->paragraph($this->faker->numberBetween(10,30)),
            "user_id" => $this->faker->numberBetween(1,150),
            "category_id" => $this->faker->numberBetween(1,4),
            "likes" => $this->faker->numberBetween(1, 150),
            "dislikes" => $this->faker->numberBetween(1, 50),
        ];
    }
}
