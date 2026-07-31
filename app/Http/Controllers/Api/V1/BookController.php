<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;

/**
 * @group Books
 *
 * Managing books
 */
class BookController extends Controller
{
    /**
     * Get books
     *
     * @queryParam page Which page to show. Example: 2
     */
    public function index()
    {
        $books = Book::with('author')->paginate(15);

        return BookResource::collection($books);
    }

    /**
     * POST books
     *
     * @bodyParam title string required Title of the book. Example: Example title
     * @bodyParam author_id integer required Id of the author. Example: 1
     */
    public function store(StoreBookRequest $request)
    {
        $book = Book::create($request->validated());

        return new BookResource($book->load('author'));
    }

    public function show(Book $book)
    {
        return new BookResource($book->load('author'));
    }

    public function update(Book $book, StoreBookRequest $request)
    {
        $book->update($request->validated());

        return new BookResource($book->load('author'));
    }

    public function destroy(Book $book)
    {
        $book->delete();

        return response()->noContent();
    }
}
