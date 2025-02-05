<?php

use App\Models\User;
use App\Models\Plan;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class, WithFaker::class);

beforeEach(function () {
    $this->withoutExceptionHandling();

    $user = User::factory()->create(['email' => 'admin@admin.com']);

    Sanctum::actingAs($user, [], 'web');
});

test('it gets plans list', function () {
    $plans = Plan::factory()
        ->count(5)
        ->create();

    $response = $this->get(route('api.plans.index'));

    $response->assertOk()->assertSee($plans[0]->name);
});

test('it stores the plan', function () {
    $data = Plan::factory()
        ->make()
        ->toArray();

    $response = $this->postJson(route('api.plans.store'), $data);

    unset($data['created_at']);
    unset($data['updated_at']);
    unset($data['deleted_at']);

    $this->assertDatabaseHas('plans', $data);

    $response->assertStatus(201)->assertJsonFragment($data);
});

test('it updates the plan', function () {
    $plan = Plan::factory()->create();

    $data = [
        'name' => fake()->name(),
        'price' => fake()->randomFloat(2, 0, 9999),
        'duration' => fake()->randomNumber(0),
        'slots' => fake()->randomNumber(0),
        'created_at' => fake()->dateTime(),
        'updated_at' => fake()->dateTime(),
    ];

    $response = $this->putJson(route('api.plans.update', $plan), $data);

    unset($data['created_at']);
    unset($data['updated_at']);
    unset($data['deleted_at']);

    $data['id'] = $plan->id;

    $this->assertDatabaseHas('plans', $data);

    $response->assertStatus(200)->assertJsonFragment($data);
});

test('it deletes the plan', function () {
    $plan = Plan::factory()->create();

    $response = $this->deleteJson(route('api.plans.destroy', $plan));

    $this->assertSoftDeleted($plan);

    $response->assertNoContent();
});
