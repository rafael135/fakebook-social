<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        

        return [
            "chat_id" => 1,
            "user_from" => User::all()->random()->id,
            "user_to" => User::all()->random()->id,
            "body" => $this->faker->text(120)
        ];
    }
}
