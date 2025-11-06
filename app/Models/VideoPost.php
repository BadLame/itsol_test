<?php

namespace App\Models;

use App\Models\Queries\VideoPostQuery;
use Database\Factories\VideoPostFactory;
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
 * @property Carbon $updated_at
 * @property Carbon $created_at
 *
 * @property User|null $author
 * @property Collection<Comment> $comments
 *
 * @method static VideoPostFactory factory($count = null, $state = [])
 * @method static VideoPostQuery|VideoPost query()
 *
 * @mixin VideoPostQuery
 */
class VideoPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
    ];

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

    function newEloquentBuilder($query): VideoPostQuery
    {
        return new VideoPostQuery($query);
    }
}
