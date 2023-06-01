<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::all()->random();
        $post = Post::all()->random();

        while ($post->user_id == $user->id) {
            $post = Post::all()->random();
        }

        return [
            "user_id" => $user->id,
            "post_id" => $post->id,
            "body" => fake()->text(130)
        ];
    }
}
