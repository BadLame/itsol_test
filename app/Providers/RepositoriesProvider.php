<?php

namespace App\Providers;

use App\Repository\Comment\CommentsRepository;
use App\Repository\Comment\SimpleCommentsRepository;
use App\Repository\News\NewsRepository;
use App\Repository\News\SimpleNewsRepository;
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
