<?php

use App\Models\Author;
use App\Models\Book;
use App\Models\User;
use Illuminate\Support\Arr;

test('api returns books list', function () {
    $user = User::factory()->create();
    $author = Author::factory()->create();
    $book = Book::factory()->create(['author_id' => $author->id]);

    $this->actingAs($user)
        ->getJson(route('books.index'))
        ->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJson([
            'data' => [Arr::only($book->toArray(), ['id', 'title'])],
        ]);
});

test('api book store successful', function () {
    $author = Author::factory()->create();
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson(route('books.store'), ['title' => 'Test Book', 'author_id' => $author->id])
        ->assertStatus(201)
        ->assertJson([
            'data' => ['title' => 'Test Book'],
        ]);
});

test('api book store fails validation', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson(route('books.store'), [])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['title', 'author_id']);
});

test('api book store fails with nonexistent author', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson(route('books.store'), ['title' => 'Test Book', 'author_id' => 999])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['author_id']);
});

test('guests cannot access books', function () {
    $this->getJson(route('books.index'))
        ->assertStatus(401);
});
