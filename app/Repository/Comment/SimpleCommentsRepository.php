<?php

namespace App\Repository\Comment;

use App\Models\Comment;
use App\Models\News;
use App\Models\Query\CommentsQuery;
use Illuminate\Contracts\Pagination\CursorPaginator;

class SimpleCommentsRepository implements CommentsRepository
{
    function paginatedCommentsFor(Comment|News $entity, int $perPage, int $nestingLevel = 1): CursorPaginator
    {
        return $this->query()
            ->orderByDesc('comments.id')
            ->ofEntity($entity)
            ->withAnswers($nestingLevel, ['author'])
            ->cursorPaginate($perPage, cursorName: Comment::getCursorName());
    }

    protected function query(): CommentsQuery
    {
        return Comment::query();
    }
}
