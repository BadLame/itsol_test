<?php

namespace App\Repository\News;

use App\Models\News;
use Illuminate\Contracts\Pagination\CursorPaginator;

interface NewsRepository
{
    /**
     * Получить список новостей для просмотра пользователями
     *
     * @param int $perPage
     * @param string|null $pageCursor
     * @return CursorPaginator<News>
     */
    function publicPaginatedList(int $perPage = 10, ?string $pageCursor = null): CursorPaginator;
}
