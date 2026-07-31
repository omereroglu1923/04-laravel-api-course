<?php

use App\Models\User;
use App\Models\Author;
use Illuminate\Support\Arr;

test('api returns authors list', function () {
    $user = User::factory()->create();
    $author = Author::factory()->create();

    $this->actingAs($user)
        ->getJson(route('authors.index'))
        ->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJson([
            'data' => [Arr::only($author->toArray(), ['id', 'name'])],
        ]);
});

test('api author store successful', function () {
    $author = ['name' => 'Test Author', 'bio' => 'Test bio'];
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson(route('authors.store'), $author)
        ->assertStatus(201)
        ->assertJson([
            'data' => Arr::only($author, ['name']),
        ]);
});

test('api author store fails validation', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson(route('authors.store'), [])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['name']);
});

test('guests cannot access authors', function () {
    $this->getJson(route('authors.index'))
        ->assertStatus(401);
});
