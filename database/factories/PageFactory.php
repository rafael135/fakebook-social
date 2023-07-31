<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Page>
 */
class PageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $creator = User::all()->random();
        $fakeTitle = $this->faker->title();

        $uniqueCode = hash("sha256", $fakeTitle . time(), false);
        while(DB::table("pages")->select(["id"])->where("uniqueCode", "=", $uniqueCode)->get()->count() > 0) {
            $uniqueCode = hash("sha256", $fakeTitle . random_int(0, 9999), false);
        }

        


        return [
            "creator_id" => $creator->id,
            "uniqueCode" => $uniqueCode,
            "name" => $fakeTitle,
            "description" => $this->faker->text(120),
            "managers" => json_encode($creator->id)

        ];
    }
}
