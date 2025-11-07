<?php

namespace App\Services\VideoPost;

use App\Models\Dtos\VideoPost\CreateVideoPostDto;
use App\Models\VideoPost;
use App\Repositories\VideoPost\VideoPostsRepository;

abstract class VideoPostsService
{
    function __construct(
        protected VideoPostsRepository $vpRepo,
    )
    {
    }

    abstract function create(CreateVideoPostDto $dto): VideoPost;
}
