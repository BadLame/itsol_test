<?php

namespace App\Http\Controllers;

use App\Http\Requests\News\CreateNewsRequest;
use App\Http\Resources\NewsResource;
use App\Repository\News\NewsRepository;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    const PER_PAGE = 10;

    function __construct(
        protected NewsRepository $repo,
    )
    {
    }

    /** Создать новость */
    function create(CreateNewsRequest $request)
    {
        return new NewsResource(
            $this->repo->create($request->toDto())
        );
    }

    /** Получение списка новостей */
    #[QueryParameter(
        'cursor',
        description: 'Курсор для получения следующей/предыдущей страницы',
        required: false,
        type: 'string'
    )]
    function list(Request $request)
    {
        return NewsResource::collection(
            $this->repo->publicPaginatedList(static::PER_PAGE, $request->query('cursor'))
        );
    }

    /**
     * Показать новость с комментариями
     *
     * @param int $id ID новости для показа
     * @return NewsResource
     */
    function show(int $id): NewsResource
    {
        return new NewsResource(
            $this->repo->publicShow($id)
        );
    }
}
