<?php

namespace App\Http\Controllers;

use App\Http\Resources\VideoPostResource;
use App\Repositories\VideoPost\VideoPostsRepository;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class VideoPostsController extends Controller
{
    const PER_PAGE = 10;

    function __construct(
        protected VideoPostsRepository $vpRepo,
    )
    {
    }

    /** Получение списка видео-постов */
    #[QueryParameter('cursor', 'Курсор перехода по страницам', type: 'string')]
    function list(): AnonymousResourceCollection
    {
        return VideoPostResource::collection(
            $this->vpRepo->publicPaginatedList(static::PER_PAGE)
        );
    }
}
