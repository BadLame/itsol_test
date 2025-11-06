<?php

namespace App\Traits;

trait EnumValues
{
    static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
