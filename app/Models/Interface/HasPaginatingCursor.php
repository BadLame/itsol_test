<?php

namespace App\Models\Interface;

interface HasPaginatingCursor
{
    static function getCursorName(): string;
}
