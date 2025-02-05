<?php

namespace Database\Factories;

use App\Models\Booking;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Booking::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'start' => fake()->dateTime(),
            'end' => fake()->dateTime(),
            'user_id' => \App\Models\User::factory(),
            'car_id' => \App\Models\Car::factory(),
            'parking_id' => 1,
            'slot_id' => 1,
            'company_id' => 1,
            'status' => 'booked'
        ];
    }
}
