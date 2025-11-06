<?php

namespace App\Models\Query;

use App\Models\Comment;
use App\Models\News;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use InvalidArgumentException;

/** @mixin Comment */
class CommentsQuery extends Builder
{
    /** Упорядочить и подгрузить отношения для показа пользователю */
    function forPublicView(): static
    {
        return $this->with(Comment::PUBLIC_RELATIONS)
            ->orderByDesc('comments.id');
    }

    /**
     * Выборка комментариев/ответов для commentable сущности
     * @param int $entityId
     * @param class-string<News|Comment> $entityType
     * @return CommentsQuery
     */
    function ofEntity(int $entityId, string $entityType): static
    {
        return $this->where(
            fn (self $q) => $q->where([
                'comments.commentable_id' => $entityId,
                'comments.commentable_type' => $entityType,
            ])
        );
    }

    /**
     * Выборка ответов для ответов до заданной глубины
     * @param bool $forPublicView Если true - автоматически упорядочит и загрузит отношения для отображения пользователю
     * @param int $nestingLevel
     * @param array $loadCommentsRelations
     * @return CommentsQuery
     */
    function withAnswers(
        bool  $forPublicView,
        int   $nestingLevel = 3,
        array $loadCommentsRelations = [],
    ): static
    {
        if ($nestingLevel <= 0) throw new InvalidArgumentException(
            'Уровень вложенности комментариев не может быть меньше 1'
        );

        $relations = $loadCommentsRelations;

        // Строим цепочку вложенных отношений снизу вверх
        for ($i = 0; $i < $nestingLevel; $i++) {
            $currentWith = $relations;
            $relations = [
                'answers' => fn (MorphMany $q) => $q->with($currentWith)
                    ->when($forPublicView, fn (MorphMany|CommentsQuery $q) => $q->forPublicView()),
            ];

            // Добавляем дополнительные отношения на каждом уровне, кроме последнего
            if ($i < $nestingLevel - 1) {
                $relations = array_merge($relations, $loadCommentsRelations);
            }
        }

        return $this->with($relations);
    }
}
