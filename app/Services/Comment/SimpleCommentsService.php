<?php

namespace App\Services\Comment;

use App\Models\Comment;
use App\Models\Dtos\Comment\CreateCommentDto;
use App\Models\Dtos\Comment\UpdateCommentDto;
use Illuminate\Validation\UnauthorizedException;

class SimpleCommentsService extends CommentsService
{
    function create(CreateCommentDto $dto): Comment
    {
        $c = new Comment;
        $c->author()->associate($dto->userId);
        $c->commentable_id = $dto->commentable_id;
        $c->commentable_type = $dto->commentable_type;
        $c->content = $dto->content;

        return $this->commentsRepo->save($c);
    }

    function delete(Comment $comment, int $deletingByUserId): bool
    {
        if ($comment->user_id != $deletingByUserId) {
            throw new UnauthorizedException;
        }

        $comment->deleted_at = now();
        $this->commentsRepo->save($comment);
        return true;
    }

    function update(UpdateCommentDto $dto, int $updatingByUserId): Comment
    {
        $comment = $dto->comment;
        if ($comment->user_id != $updatingByUserId) {
            throw new UnauthorizedException;
        }

        $comment->content = $dto->content;
        return $this->commentsRepo->save($comment);
    }
}
