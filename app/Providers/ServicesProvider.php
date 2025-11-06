<?php

namespace App\Providers;

use App\Repositories\Comment\CommentsRepository;
use App\Repositories\News\NewsRepository;
use App\Services\Comment\CommentsService;
use App\Services\Comment\SimpleCommentsService;
use App\Services\News\NewsService;
use App\Services\News\SimpleNewsService;
use Illuminate\Support\ServiceProvider;

class ServicesProvider extends ServiceProvider
{
    function register(): void
    {
        $this->app->singleton(
            NewsService::class,
            fn () => new SimpleNewsService(app(NewsRepository::class))
        );
        $this->app->singleton(
            CommentsService::class,
            fn () => new SimpleCommentsService(app(CommentsRepository::class))
        );
    }

    function provides(): array
    {
        return [
            SimpleCommentsService::class,
            SimpleNewsService::class,
        ];
    }
}
