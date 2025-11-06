<?php

namespace App\Models\Enum;

enum CursorNames: string
{
    case NEWS = 'news_cursor';
    case COMMENTS = 'comments_cursor';
}
