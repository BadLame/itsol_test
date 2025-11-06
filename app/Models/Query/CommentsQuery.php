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
    /** Выборка комментариев commentable сущности */
    function ofEntity(News|Comment $entity): static
    {
        return $this->where(
            fn (self $q) => $q->where([
                'comments.commentable_id' => $entity->id,
                'comments.commentable_type' => $entity::class,
            ])
        );
    }

    /** Выборка ответов для ответов до заданной глубины */
    function withAnswers(int $nestingLevel = 1, array $loadCommentsRelations = []): static
    {
        if ($nestingLevel <= 0) throw new InvalidArgumentException(
            'Уровень вложенности комментариев не может быть меньше 1'
        );

        $relations = $loadCommentsRelations;

        // Строим цепочку вложенных отношений снизу вверх
        for ($i = 0; $i < $nestingLevel; $i++) {
            $currentWith = $relations;
            $relations = ['answers' => fn (MorphMany $q) => $q->with($currentWith)];

            // Добавляем дополнительные отношения на каждом уровне, кроме последнего
            if ($i < $nestingLevel - 1) {
                $relations = array_merge($relations, $loadCommentsRelations);
            }
        }

        return $this->with($relations);
    }
}
