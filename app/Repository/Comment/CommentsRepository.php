<?php

namespace App\Repository\Comment;

use App\Models\Comment;
use App\Models\News;
use Illuminate\Contracts\Pagination\CursorPaginator;

interface CommentsRepository
{
    /** Получить комментарии к commentable сущности */
    function paginatedCommentsFor(News|Comment $entity, int $perPage, int $nestingLevel = 1): CursorPaginator;
}
