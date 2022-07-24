<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class Post_TagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "post_id" => $this->faker->numberBetween(1,10),
            "tag_id" => $this->faker->numberBetween(1,4)
        ];
    }
}
