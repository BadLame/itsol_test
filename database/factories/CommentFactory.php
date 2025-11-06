<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Enums\Commentables;
use App\Models\News;
use App\Models\User;
use App\Models\VideoPost;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    function definition(): array
    {
        $commentable = Arr::random(Commentables::values());

        return [
            'user_id' => User::factory(),
            'commentable_id' => $commentable::factory(),
            'commentable_type' => $commentable,

            'content' => fake()->text(),
        ];
    }

    function withCommentable(News|Comment|VideoPost $commentable): self
    {
        return $this->state([
            'commentable_id' => $commentable->id,
            'commentable_type' => $commentable::class,
        ]);
    }
}
