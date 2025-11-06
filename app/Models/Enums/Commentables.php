<?php

namespace App\Models\Enums;

use App\Models\Comment;
use App\Models\News;
use App\Models\VideoPost;
use App\Traits\EnumValues;

enum Commentables: string
{
    use EnumValues;

    case News = News::class;
    case Comment = Comment::class;
    case VideoPost = VideoPost::class;
}
