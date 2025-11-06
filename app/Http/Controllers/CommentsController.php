<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comment\CommentsListRequest;
use App\Http\Requests\Comment\CreateCommentRequest;
use App\Http\Requests\Comment\DeleteCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Dtos\Comment\UpdateCommentDto;
use App\Repositories\Comment\CommentsRepository;
use App\Services\Comment\CommentsService;
use Dedoc\Scramble\Attributes\PathParameter;
use Dedoc\Scramble\Attributes\QueryParameter;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CommentsController extends Controller
{
    const PER_PAGE = 10;
    const DEFAULT_COMMENTS_NESTING_LEVEL = 3;

    function __construct(
        protected CommentsRepository $commentsRepo,
        protected CommentsService    $commentsService,
    )
    {
    }

    /** Создание комментария */
    #[Response(404, 'Commentable сущность не найдена')]
    function create(CreateCommentRequest $request): CommentResource
    {
        $comment = $this->commentsService->create($request->toDto());
        $this->commentsRepo->loadRelations($comment, Comment::PUBLIC_RELATIONS);

        return new CommentResource($comment);
    }

    /** Редактирование комментария */
    #[PathParameter('id', 'ID Комментария')]
    #[Response(403, 'Нельзя редактировать чужой комментарий')]
    #[Response(404, 'Комментарий не найден')]
    function update(UpdateCommentRequest $request, int $id): CommentResource
    {
        $comment = $this->commentsRepo->findOrFail($id);
        $comment = $this->commentsService->update(
            new UpdateCommentDto($comment, $request->text),
            $request->user_id
        );
        $this->commentsRepo->loadRelations($comment, Comment::PUBLIC_RELATIONS);

        return new CommentResource($comment);
    }

    /** Удаление комментария */
    #[PathParameter('id', 'ID Комментария')]
    #[Response(403, 'Нельзя удалить чужой комментарий')]
    #[Response(404, 'Комментарий не найден')]
    function delete(DeleteCommentRequest $request, int $id)
    {
        $comment = $this->commentsRepo->findOrFail($id);
        $this->commentsService->delete($comment, $request->user_id);

        return response()->noContent(200);
    }

    /** Получить пагинированный список комментариев для commentable сущности */
    #[QueryParameter('cursor', 'Курсор для перехода по страницам', type: 'string')]
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
