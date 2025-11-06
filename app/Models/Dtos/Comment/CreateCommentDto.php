<?php

namespace App\Models\Dtos\Comment;

/** Хранит данные для создания комментария */
class CreateCommentDto
{
    function __construct(
        public int    $commentable_id,
        public string $commentable_type,
        public int    $userId,
        public string $content,
    )
    {
    }
}
