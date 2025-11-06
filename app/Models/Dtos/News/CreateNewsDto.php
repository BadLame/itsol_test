<?php

namespace App\Models\Dtos\News;

/** DTO с необходимыми для создания новости данными */
class CreateNewsDto
{
    function __construct(
        public string $title,
        public string $content,
        public int    $userId,
    )
    {
    }
}
