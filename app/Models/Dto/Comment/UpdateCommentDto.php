<?php

namespace App\Models\Dto\Comment;

use App\Models\Comment;

/** Хранит данные для создания комментария */
class UpdateCommentDto
{
    function __construct(
        public Comment $comment,
        public string  $content,
    )
    {
    }
}
