<?php

namespace App\Repositories\News;

use App\Models\News;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

interface NewsRepository
{
    /**
     * Получить список новостей для просмотра пользователями
     *
     * @param int $perPage
     * @return CursorPaginator<News>
     */
    function publicPaginatedList(int $perPage = 10): CursorPaginator;

    /**
     * Показ новости со связанными данными
     * @throws NotFoundHttpException
     */
    function publicShow(int $id): News;

    /** Сохранить новую запись новости */
    function create(News $news): News;
}
