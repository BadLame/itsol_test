<?php

namespace App\Repository\Comment;

use Illuminate\Contracts\Pagination\CursorPaginator;

interface CommentsRepository
{
    /** Получить комментарии к commentable сущности */
    function paginatedCommentsFor(
        int    $entityId,
        string $entityType,
        int    $perPage,
        int    $nestingLevel = 1
    ): CursorPaginator;
}
