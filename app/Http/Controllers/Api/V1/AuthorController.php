<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAuthorRequest;
use App\Http\Resources\AuthorResource;
use App\Models\Author;

/**
 * @group Authors
 *
 * Managing authors
 */
class AuthorController extends Controller
{
    /**
     * Get authors
     *
     * @queryParam page Which page to show. Example: 2
     */
    public function index()
    {
        $authors = Author::paginate(15);

        return AuthorResource::collection($authors);
    }

    /**
     * POST authors
     *
     * @bodyParam name string required Name of the author. Example: Example name
     * @bodyParam bio string Bio of the author. Example: Example bio
     */
    public function store(StoreAuthorRequest $request)
    {
        $author = Author::create($request->validated());

        return new AuthorResource($author);
    }

    public function show(Author $author)
    {
        return new AuthorResource($author);
    }

    public function update(Author $author, StoreAuthorRequest $request)
    {
        $author->update($request->validated());

        return new AuthorResource($author);
    }

    public function destroy(Author $author)
    {
        if ($author->books()->exists()) {
            return response()->json([
                'message' => 'This author has related books. Remove or reassign them first.',
            ], 409);
        }

        $author->delete();

        return response()->noContent();
    }
}
