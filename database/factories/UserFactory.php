<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Nette\Utils\Random;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->name();

        $uniqueUrl = str_replace(' ', '-', $name) . "-" . random_int(9999, 99999999);

        while( DB::table("users")->select()->where("uniqueUrl", "=", $uniqueUrl)->count() > 0 ) {
            $uniqueUrl = str_replace(' ', '-', $name) . "-" . random_int(9999, 99999999);
        }

        return [
            "uniqueUrl" => $uniqueUrl, // Anterior: Random::generate(8, "0-9a-z")
            'name' => $name,
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
