<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

Route::post('login', LoginController::class);

Route::post('register', RegisterController::class);

Route::get('/', function () {
    return view('welcome');
});
