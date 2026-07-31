<?php

use App\Http\Controllers\Api\V1\CategoryController; // V1 eklendi
use App\Http\Controllers\Api\V1\ProductController;   // V1 eklendi
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Orion\Facades\Orion;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\OrionCategoryController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('register', RegisterController::class);
Route::post('login', LoginController::class);

Route::prefix('v1')->group(function () { // sadece bu ikisi v1'e taşınıyor
    Route::apiResource('categories', CategoryController::class);
    Route::get('products', [ProductController::class, 'index']);
});

Orion::resource('tags', TagController::class);

Orion::resource('orion-categories', OrionCategoryController::class);
