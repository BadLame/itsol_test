<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property Carbon $updated_at
 * @property Carbon $created_at
 */
class User extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    // Relations

    function news(): HasMany
    {
        return $this->hasMany(News::class);
    }
}
