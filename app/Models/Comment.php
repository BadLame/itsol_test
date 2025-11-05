<?php

namespace App\Models;

use App\Models\Query\CommentsQuery;
use Database\Factories\CommentFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $commentable_id
 * @property class-string<News|Comment> $commentable_type
 * @property int $user_id
 * @property string $content
 * @property Carbon $updated_at
 * @property Carbon $created_at
 * @property Carbon $deleted_at
 *
 * @property Collection<Comment> $answers
 * @property User $author
 * @property News|Comment|null $commentable
 *
 * @method static CommentFactory factory($count = null, $state = [])
 * @method static CommentsQuery|Comment query()
 */
class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'deleted_at',
    ];

    // Relations

    function answers(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    // Methods

    function delete(): void
    {
        $this->deleted_at = now();
    }

    function isDeleted(): bool
    {
        return !is_null($this->deleted_at);
    }

    // Misc

    function newEloquentBuilder($query): CommentsQuery
    {
        return new CommentsQuery($query);
    }
}
