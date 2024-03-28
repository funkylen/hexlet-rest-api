<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// TODO: Posts
// GET /posts - index - получить все посты
// GET /posts/create - create - показать форму создания поста
// POST /posts - store - создать пост
// GET /posts/:id/edit - edit - показать форму редактирования поста
// PUT /posts/:id - update - редактировать пост
// DELETE /posts/:id - destroy - удалить пост
// GET /posts/:id - show - показать пост
Route::resource('posts', PostController::class);

// TODO: Comments
Route::resource('posts.comments', CommentController::class)->only('store', 'destroy');
