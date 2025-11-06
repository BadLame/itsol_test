<?php

namespace App\Repositories\Comment;

use App\Models\Comment;
use App\Models\Queries\CommentsQuery;
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
            ->cursorPaginate($perPage);
    }

    function findOrFail(int $id): Comment
    {
        /** @var Comment $comment */
        $comment = $this->query()->findOrFail($id);
        return $comment;
    }

    function save(Comment $comment): Comment
    {
        $comment->save();
        return $comment;
    }

    function loadRelations(Comment &$comment, array $relations): Comment
    {
        $comment->load($relations);
        return $comment;
    }

    protected function query(): CommentsQuery
    {
        return Comment::query();
    }
}
