<?php

namespace App\Providers;

use App\Repository\News\NewsRepository;
use App\Repository\News\SimpleNewsRepository;
use Illuminate\Support\ServiceProvider;

class RepositoriesProvider extends ServiceProvider
{
    function register(): void
    {
        $this->app->singleton(NewsRepository::class, fn () => new SimpleNewsRepository);
    }

    function provides(): array
    {
        return [
            SimpleNewsRepository::class,
        ];
    }
}
