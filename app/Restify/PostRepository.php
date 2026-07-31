<?php

namespace App\Restify;

use App\Models\Post;
use App\Restify\Repository;
use Binaryk\LaravelRestify\Http\Requests\RestifyRequest;


class PostRepository extends Repository
{
    public static string $model = Post::class;

    public function fields(RestifyRequest $request): array
    {
        return [
            id(),
            field('title')->required(),
            textarea('body')->required(), // field('body')->textarea() yerine
            field('created_at')->datetime()->readonly(),
            field('updated_at')->datetime()->readonly(),
        ];
    }
}
