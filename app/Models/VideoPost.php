<?php

namespace App\Models;

use App\Models\Queries\VideoPostQuery;
use Database\Factories\VideoPostFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Carbon;
use Plank\Mediable\Media;
use Plank\Mediable\Mediable;
use Plank\Mediable\MediableInterface;

/**
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property Carbon $updated_at
 * @property Carbon $created_at
 *
 * @property User|null $author
 * @property Collection<Comment> $comments
 * @property Collection<Media> $video В коллекции всегда только одно видео
 *
 * @method static VideoPostFactory factory($count = null, $state = [])
 * @method static VideoPostQuery|VideoPost query()
 *
 * @mixin VideoPostQuery
 */
class VideoPost extends Model implements MediableInterface
{
    use HasFactory, Mediable;

    const PUBLIC_RELATIONS = ['author', 'video'];
    const VIDEO_TAG = 'video';
    const VIDEO_DISK = 'video_posts';

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

    function video(): MorphToMany
    {
        return $this->media()->limit(1);
    }

    // Misc

    function newEloquentBuilder($query): VideoPostQuery
    {
        return new VideoPostQuery($query);
    }
}
