<?php

namespace App\Http\Controllers;

use App\Http\Resources\NewsResource;
use App\Models\News;
use Dedoc\Scramble\Attributes\QueryParameter;

class NewsController extends Controller
{
    const PER_PAGE = 10;

    /** Получение списка новостей */
    #[QueryParameter(
        'cursor',
        description: 'Курсор для получения следующей/предыдущей страницы',
        required: false,
        type: 'string'
    )]
    function list()
    {
        return NewsResource::collection(
            News::query()->orderByDesc('id')->cursorPaginate(static::PER_PAGE)
        );
    }
}
