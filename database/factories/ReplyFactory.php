<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ReplyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "comment_id" => $this->faker->numberBetween(1, 35000),
            "user_id" => $this->faker->numberBetween(1, 4000),
            "description" => $this->faker->paragraph($this->faker->numberBetween(5,15)),
            "likes" => $this->faker->numberBetween(1, 150),
            "dislikes" => $this->faker->numberBetween(1, 50),
        ];
    }
}
