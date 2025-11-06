<?php

namespace App\Repository\Comment;

use App\Models\Comment;
use App\Models\Query\CommentsQuery;
use Illuminate\Contracts\Pagination\CursorPaginator;

class SimpleCommentsRepository implements CommentsRepository
{
    function paginatedCommentsFor(
        int    $entityId,
        string $entityType,
        int    $perPage,
        int    $nestingLevel = 1
    ): CursorPaginator
    {
        return $this->query()
            ->forPublicView()
            ->ofEntity($entityId, $entityType)
            ->withAnswers(true, $nestingLevel)
            ->cursorPaginate($perPage, cursorName: Comment::getCursorName());
    }

    protected function query(): CommentsQuery
    {
        return Comment::query();
    }
}
