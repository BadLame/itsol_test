<?php

namespace App\Http\Controllers;

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
}
