<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quiz>
 */
class QuizFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "number" => 1,
            "quiz_master" => "Tim",
            "quiz_winner" => "Felix",
            "started_at" => $this->faker->dateTime(),
            "location_id" => 1,
        ];
    }
}
