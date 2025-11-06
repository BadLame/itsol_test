<?php

namespace App\Providers;

use App\Repositories\Comment\CommentsRepository;
use App\Repositories\Comment\SimpleCommentsRepository;
use App\Repositories\News\NewsRepository;
use App\Repositories\News\SimpleNewsRepository;
use Illuminate\Support\ServiceProvider;

class RepositoriesProvider extends ServiceProvider
{
    function register(): void
    {
        $this->app->singleton(CommentsRepository::class, fn () => new SimpleCommentsRepository);
        $this->app->singleton(NewsRepository::class, fn () => new SimpleNewsRepository);
    }

    function provides(): array
    {
        return [
            SimpleCommentsRepository::class,
            SimpleNewsRepository::class,
        ];
    }
}
