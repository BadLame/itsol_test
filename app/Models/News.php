<?php

namespace App\Models;

use App\Models\Query\NewsQuery;
use Database\Factories\NewsFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
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
 * @property Collection<Comment> $comments
 *
 * @method static NewsFactory factory($count = null, $state = [])
 * @method static NewsQuery|News query()
 *
 * @mixin NewsQuery
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

    function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    // Misc

    function newEloquentBuilder($query): NewsQuery
    {
        return new NewsQuery($query);
    }
}
