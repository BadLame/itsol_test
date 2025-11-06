<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\VideoPost;
use Illuminate\Database\Eloquent\Factories\Factory;

class VideoPostFactory extends Factory
{
    protected $model = VideoPost::class;

    function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->colorName(),
        ];
    }
}
