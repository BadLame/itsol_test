<?php

namespace App\Http\Controllers;

use App\Http\Requests\VideoPost\CreateVideoPostRequest;
use App\Http\Resources\VideoPostResource;
use App\Models\VideoPost;
use App\Repositories\VideoPost\VideoPostsRepository;
use App\Services\VideoPost\VideoPostsService;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class VideoPostsController extends Controller
{
    const PER_PAGE = 10;

    function __construct(
        protected VideoPostsRepository $vpRepo,
        protected VideoPostsService    $vpService
    )
    {
    }

    /** Создание видео-поста */
    function create(CreateVideoPostRequest $request): VideoPostResource
    {
        $vp = $this->vpService->create($request->toDto());
        $this->vpRepo->loadRelations($vp, VideoPost::PUBLIC_RELATIONS);

        return new VideoPostResource($vp);
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
