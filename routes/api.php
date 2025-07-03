<?php

use Illuminate\Support\Facades\Route;

// Public endpoints
Route::get('/posts', \App\Http\Controllers\PostListHandler::class);
Route::get('/posts/{id}', \App\Http\Controllers\PostShowHandler::class);
Route::get('/categories/{id}/posts', \App\Http\Controllers\CategoryPostsHandler::class);
Route::get('/comments', \App\Http\Controllers\CommentListHandler::class);
Route::get('/comments/{id}', \App\Http\Controllers\CommentShowHandler::class);

// Protected endpoints
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/posts', \App\Http\Controllers\PostCreateHandler::class);
    Route::post('/posts/{id}/comments', \App\Http\Controllers\CommentCreateHandler::class);
    Route::put('/posts/{id}', \App\Http\Controllers\PostUpdateHandler::class);
    Route::delete('/posts/{id}', \App\Http\Controllers\PostDeleteHandler::class);
    Route::put('/comments/{id}', \App\Http\Controllers\CommentUpdateHandler::class);
    Route::delete('/comments/{id}', \App\Http\Controllers\CommentDeleteHandler::class);
}); 