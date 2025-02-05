<?php

namespace Database\Factories;

use App\Models\Car;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Car::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'number' => fake()->randomNumber(),
            'brand' => 'BMW',
            'model' => 'E30',
            'color' => fake()->hexcolor(),
            'code' => fake()->text(),
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
