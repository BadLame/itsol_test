<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\VideoPost;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Plank\Mediable\Facades\MediaUploader;

class VideoPostFactory extends Factory
{
    protected $model = VideoPost::class;

    function configure(): static
    {
        return $this->afterCreating(function (VideoPost $vp) {
            $video = UploadedFile::fake()->create(
                'video.mp4',
                512,
                'video/mp4'
            );
            $media = MediaUploader::fromSource($video)
                ->toDisk('video_posts')
                ->upload();
            $vp->attachMedia($media, VideoPost::VIDEO_TAG);
        });
    }

    function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->colorName(),
        ];
    }
}
