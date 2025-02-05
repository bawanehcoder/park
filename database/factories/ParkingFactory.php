<?php

namespace Database\Factories;

use App\Models\Parking;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class ParkingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Parking::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'address' => fake()->address(),
            'company_id' => 1,
        ];
    }
}
