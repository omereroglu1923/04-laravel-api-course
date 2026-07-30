<?php

use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Arr;

test('api returns categories list', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();

    $this->actingAs($user)
        ->getJson(route('categories.index'))
        ->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJson([
            'data' => [Arr::only($category->toArray(), ['id', 'name'])],
        ]);
});

test('api category store successful', function () {
    $category = ['name' => 'Test Category', 'description' => 'Test description'];
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson(route('categories.store'), $category)
        ->assertStatus(201)
        ->assertJson([
            'data' => Arr::only($category, ['name']),
        ]);
});
