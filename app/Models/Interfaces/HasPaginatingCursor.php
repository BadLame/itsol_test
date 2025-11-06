<?php

namespace App\Models\Interfaces;

interface HasPaginatingCursor
{
    static function getCursorName(): string;
}
