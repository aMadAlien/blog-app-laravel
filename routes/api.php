<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('auth')
    ->controller(AuthController::class)
    ->group(function () {
        Route::post('register', 'register');
        Route::post('login', 'login');
    });

Route::controller(PostsController::class)
    ->group(function () {
        Route::get('posts', 'index');
        Route::delete('posts/{post}', 'destroy');
        Route::put('posts/{post}', 'update');
        Route::post('posts', 'store');
    });
