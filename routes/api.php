<?php

use App\Http\Controllers\CommentsController;
use App\Http\Controllers\NewsController;
use Illuminate\Support\Facades\Route;

Route::name('news.')->prefix('/news/')->group(function () {
    Route::get('/', [NewsController::class, 'list'])->name('list');
    Route::get('/{id}', [NewsController::class, 'show'])->name('show')->where('id', '[0-9]+');
    Route::post('/', [NewsController::class, 'create'])->name('create');
});

Route::name('comments.')->prefix('/comments/')->group(function () {
    Route::get('/', [CommentsController::class, 'list'])->name('list');
    Route::post('/', [CommentsController::class, 'create'])->name('create');
    Route::patch('/{id}', [CommentsController::class, 'update'])->name('update')->where('id', '[0-9]+');
    Route::delete('/{id}', [CommentsController::class, 'delete'])->name('delete')->where('id', '[0-9]+');
});
