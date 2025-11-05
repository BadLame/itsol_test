<?php

use App\Http\Controllers\NewsController;
use Illuminate\Support\Facades\Route;

Route::name('news.')->prefix('/news/')->group(function () {
    Route::get('/', [NewsController::class, 'list'])->name('list');
    Route::get('/{id}', [NewsController::class, 'show'])->name('show')->where('id', '[0-9]+');
});
