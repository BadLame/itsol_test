<?php

namespace App\Repositories\Comment;

use App\Models\Comment;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

interface CommentsRepository
{
    /** Получить комментарии к commentable сущности */
    function paginatedCommentsFor(
        int    $entityId,
        string $entityType,
        int    $perPage,
        int    $nestingLevel = 1
    ): CursorPaginator;

    /** @throws NotFoundHttpException */
    function findOrFail(int $id): Comment;

    /** Создать новый или обновить существующий комментарий */
    function save(Comment $comment): Comment;

    /** Подгрузить отсутствующие отношения записи */
    function loadRelations(Comment &$comment, array $relations): Comment;
}
