<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subscription>
 */
class SubscriptionFactory extends Factory
{
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
            'parking_id' => 1,
            'plan_id' => 1,
            'car_id' => 1,
        ];
    }
}
