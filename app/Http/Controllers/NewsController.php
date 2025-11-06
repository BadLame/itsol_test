<?php

namespace App\Http\Controllers;

use App\Http\Requests\News\CreateNewsRequest;
use App\Http\Resources\NewsResource;
use App\Repositories\News\NewsRepository;
use App\Services\News\NewsService;
use Dedoc\Scramble\Attributes\PathParameter;
use Dedoc\Scramble\Attributes\QueryParameter;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class NewsController extends Controller
{
    const PER_PAGE = 10;

    function __construct(
        protected NewsRepository $newsRepo,
        protected NewsService    $newsService
    )
    {
    }

    /** Создать новость */
    function create(CreateNewsRequest $request): NewsResource
    {
        return new NewsResource(
            $this->newsService->create($request->toDto())
        );
    }

    /** Получение списка новостей */
    #[QueryParameter(
        'news_cursor',
        description: 'Курсор для получения следующей/предыдущей страницы',
        required: false,
        type: 'string'
    )]
    function list(): AnonymousResourceCollection
    {
        return NewsResource::collection(
            $this->newsRepo->publicPaginatedList(static::PER_PAGE)
        );
    }

    /** Показать новость */
    #[PathParameter('id', 'ID новости для показа')]
    #[Response(404, 'Новость не найдена')]
    function show(int $id): NewsResource
    {
        return new NewsResource(
            $this->newsRepo->publicShow($id)
        );
    }
}
