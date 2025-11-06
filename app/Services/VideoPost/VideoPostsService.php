<?php

namespace App\Services\VideoPost;

use App\Repositories\VideoPost\VideoPostsRepository;

abstract class VideoPostsService
{
    function __construct(
        protected VideoPostsRepository $vpRepo,
    )
    {
    }
}
