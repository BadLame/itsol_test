<?php

use App\Http\Controllers\NewsController;
use Illuminate\Support\Facades\Route;

Route::name('news.')->prefix('/news/')->group(function () {
    Route::get('/', [NewsController::class, 'list'])->name('list');
});
