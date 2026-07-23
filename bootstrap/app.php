<?php

use App\Http\Middleware\AlwaysAcceptJson; // yeni import
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request; // yeni import
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException; // yeni import

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->prepend(AlwaysAcceptJson::class); // yeni eklenen satır
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // yeni eklenen blok — 404 hatasını özelleştiriyoruz
        $exceptions->renderable(function (NotFoundHttpException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Object not found'], 404);
            }
        });
    })->create();