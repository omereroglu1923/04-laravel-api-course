<?php

use App\Http\Controllers\Api\V2\CategoryController;
use App\Http\Controllers\Api\V2\ProductController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api'])->group(function () {
    Route::apiResource('categories', CategoryController::class);
    Route::get('products', [ProductController::class, 'index']);
});
