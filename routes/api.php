<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:api')->group(function () {

    Route::get('/posts', [PostController::class, 'index']);
    Route::post('/posts', [PostController::class, 'store']);
    Route::get('/posts/{id}', [PostController::class, 'show']);

    Route::get('/users-by-views', [UserController::class, 'indexByViews']);
    Route::get('/profile', [UserController::class, 'profile']);
    Route::post('/profile/image', [UserController::class, 'uploadProfileImage']);
});
