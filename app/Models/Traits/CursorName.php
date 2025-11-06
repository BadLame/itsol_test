<?php

namespace App\Models\Traits;

use App\Models\Comment;
use App\Models\Enums\CursorNames;
use App\Models\News;
use InvalidArgumentException;

trait CursorName
{
    static function getCursorName(): string
    {
        return match (static::class) {
            News::class => CursorNames::NEWS->value,
            Comment::class => CursorNames::COMMENTS->value,
            default => throw new InvalidArgumentException(
                'Cursor name for class ' . static::class . 'not implemented'
            ),
        };
    }
}
