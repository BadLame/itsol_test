<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\News;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NewsFactory extends Factory
{
    protected $model = News::class;

    function definition(): array
    {
        return [
            'user_id' => User::factory(),

            'title' => fake()->colorName(),
            'content' => fake()->text(),
        ];
    }

    function withComments(?int $count = null): self
    {
        return $this->afterCreating(function (News $news) use ($count) {
            Comment::factory($count ?: rand(1, 3))
                ->withCommentable($news)
                ->create();
        });
    }
}
