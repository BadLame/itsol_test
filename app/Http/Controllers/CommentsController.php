<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comment\CommentsListRequest;
use App\Http\Resources\CommentResource;
use App\Repository\Comment\CommentsRepository;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CommentsController extends Controller
{
    const PER_PAGE = 10;
    const DEFAULT_COMMENTS_NESTING_LEVEL = 3;

    function __construct(
        protected CommentsRepository $commentsRepo,
    )
    {
    }

    /** Получить пагинированный список комментариев для commentable сущности */
    #[Response(404, 'Commentable сущность не найдена')]
    function list(CommentsListRequest $request): AnonymousResourceCollection
    {
        return CommentResource::collection(
            $this->commentsRepo->paginatedCommentsFor(
                $request->entity_id,
                $request->entity_type,
                static::PER_PAGE,
                $request->comments_nesting_level ?: static::DEFAULT_COMMENTS_NESTING_LEVEL
            )
        );
    }
}
