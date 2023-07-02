<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FriendRelation>
 */
class FriendRelationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user_from = User::all()->random()->id;
        $user_to = User::all()->random()->id;

        while($user_from == $user_to && DB::table("friends_relations")->select()->where("user_from", "=", $user_from)->where("user_to", "=", $user_to)->count() > 0) {
            $user_to = User::all()->random()->id;
        }

        return [
            "user_from" => $user_from,
            "user_to" => $user_to
        ];
    }
}
