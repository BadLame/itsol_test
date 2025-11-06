<?php

namespace App\Models\Enum;

use App\Models\Comment;
use App\Models\News;
use App\Traits\EnumValues;

enum Commentables: string
{
    use EnumValues;

    case News = News::class;
    case Comment = Comment::class;
}
