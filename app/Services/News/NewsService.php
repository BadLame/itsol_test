<?php

namespace App\Services\News;

use App\Models\Dto\News\CreateNewsDto;
use App\Models\News;
use App\Repositories\News\NewsRepository;

abstract class NewsService
{
    function __construct(
        protected NewsRepository $newsRepo,
    )
    {
    }

    abstract function create(CreateNewsDto $dto): News;
}
