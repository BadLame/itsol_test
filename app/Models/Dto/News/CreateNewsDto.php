<?php

namespace App\Models\Dto\News;

/** DTO с необходимыми для создания новости данными */
class CreateNewsDto
{
    function __construct(
        public string $title,
        public string $content,
        public int $user_id,
    )
    {
    }
}
