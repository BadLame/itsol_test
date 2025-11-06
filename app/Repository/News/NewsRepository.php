<?php

namespace App\Repository\News;

use App\Models\Dto\News\CreateNewsDto;
use App\Models\News;
use Illuminate\Contracts\Pagination\CursorPaginator;

interface NewsRepository
{
    /**
     * Получить список новостей для просмотра пользователями
     *
     * @param int $perPage
     * @return CursorPaginator<News>
     */
    function publicPaginatedList(int $perPage = 10): CursorPaginator;

    /** Показ новости со связанными данными */
    function publicShow(int $id): News;

    /** Сохранить новую запись новости */
    function create(CreateNewsDto $dto): News;
}
