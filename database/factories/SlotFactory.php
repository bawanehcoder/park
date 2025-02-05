<?php

namespace Database\Factories;

use App\Models\Slot;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class SlotFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Slot::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => fake()->text(),
            'status' => fake()->word(),
            'parking_id' => 1,
        ];
    }
}
