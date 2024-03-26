<?php

namespace Database\Factories;

use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{

    protected $model = Profile::class;

    public function definition(): array
    {
        return [
            "last_name" => fake()->lastName(),
            "first_name" => fake()->firstName(),
            "img" => fake()->image(),
            "status" => fake()->randomElement(['active', 'inactive', 'awaiting']),
            "created_at" => fake()->date(),
            "updated_at" => fake()->date(),
        ];
    }
}
