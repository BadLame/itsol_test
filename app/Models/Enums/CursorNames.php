<?php

namespace App\Models\Enums;

enum CursorNames: string
{
    case NEWS = 'news_cursor';
    case COMMENTS = 'comments_cursor';
}
