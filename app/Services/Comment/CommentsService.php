<?php

namespace App\Services\Comment;

use App\Models\Comment;
use App\Models\Dtos\Comment\CreateCommentDto;
use App\Models\Dtos\Comment\UpdateCommentDto;
use App\Repositories\Comment\CommentsRepository;
use Illuminate\Validation\UnauthorizedException;

abstract class CommentsService
{
    function __construct(
        protected CommentsRepository $commentsRepo,
    )
    {
    }

    abstract function create(CreateCommentDto $dto): Comment;

    /** @throws UnauthorizedException */
    abstract function update(UpdateCommentDto $dto, int $updatingByUserId): Comment;

    /** @throws UnauthorizedException */
    abstract function delete(Comment $comment, int $deletingByUserId): bool;
}
