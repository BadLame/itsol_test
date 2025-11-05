<?php

namespace App\Models;

use Database\Factories\NewsFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $content
 * @property Carbon $updated_at
 * @property Carbon $created_at
 *
 * @property User $author
 *
 * @method static NewsFactory factory($count = null, $state = [])
 */
class News extends Model
{
    use HasFactory;

    protected $table = 'news';

    // Relations

    function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
