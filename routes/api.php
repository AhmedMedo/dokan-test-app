<?php

use Illuminate\Support\Facades\Route;

// Public endpoints
Route::get('/posts', [App\Http\Controllers\PostController::class, 'index']);
Route::get('/posts/{id}', [App\Http\Controllers\PostController::class, 'show']);
Route::get('/categories/{id}/posts', [App\Http\Controllers\CategoryController::class, 'posts']);

// Protected endpoints
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/posts', [App\Http\Controllers\PostController::class, 'store']);
    Route::post('/posts/{id}/comments', [App\Http\Controllers\CommentController::class, 'store']);
    Route::put('/posts/{id}', [App\Http\Controllers\PostController::class, 'update']);
    Route::delete('/posts/{id}', [App\Http\Controllers\PostController::class, 'destroy']);
    Route::put('/comments/{id}', [App\Http\Controllers\CommentController::class, 'update']);
    Route::delete('/comments/{id}', [App\Http\Controllers\CommentController::class, 'destroy']);
}); 