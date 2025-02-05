<?php

namespace Database\Factories;

use App\Models\Plan;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Plan::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'price' => fake()->randomFloat(2, 0, 9999),
            'duration' => fake()->randomNumber(0),
            'slots' => fake()->randomNumber(0),
        ];
    }
}
