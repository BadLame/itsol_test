<?php

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\VideoPostsController;
use App\Models\User;
use App\Models\VideoPost;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class VideoPostsControllerTest extends TestCase
{
    // List

    function testListReturnsPaginatedVideoPosts(): void
    {
        $vps = VideoPost::factory(VideoPostsController::PER_PAGE + 1)->create();
        /** @var VideoPost $shouldBeOnSecondPage */
        $shouldBeOnSecondPage = $vps->sortBy('id')->first();

        $response = $this->getJson(route('video_posts.list'))
            ->assertSuccessful()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'author_id',
                        'author' => ['id', 'name'],
                        'video' => ['alt', 'url'],
                        'title',
                        'created_at',
                    ],
                ],
                'links' => ['first', 'last', 'prev', 'next'],
                'meta' => ['path', 'per_page', 'next_cursor', 'prev_cursor'],
            ]);

        // Проверка работы пагинации
        $this->getJson(route('video_posts.list', [
            'cursor' => $response->json('meta.next_cursor'),
        ]))
            ->assertSuccessful()
            ->assertJsonFragment([
                'id' => $shouldBeOnSecondPage->id,
                'title' => $shouldBeOnSecondPage->title,
            ]);
    }

    // Create

    function testCreateCreatesVideoPost(): void
    {
        $testFile = new \Symfony\Component\HttpFoundation\File\UploadedFile(
            __DIR__ . '/../../../test_files/sample.mp4',
            'sample.mp4',
            'video/mp4',
        );
        $video = UploadedFile::createFromBase($testFile, true);
        $request = [
            'title' => fake()->colorName(),
            'video' => $video,
            'user_id' => User::factory()->create()->id,
        ];

        $response = $this->postJson(route('video_posts.create'), $request)
            ->assertSuccessful()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'author_id',
                    'title',
                    'created_at',
                    'author' => ['id', 'name'],
                    'video' => ['alt', 'url'],
                ],
            ]);

        $this->assertDatabaseHas('video_posts', [
            'title' => $request['title'],
            'user_id' => $request['user_id'],
        ]);

        $this->assertNotEmpty(
            VideoPost::query()->find($response->json('data.id'))->video()->first()
        );
    }

    function testCreateVideoFileValidation(): void
    {
        $maxFileSizeInKb = (int)(config('mediable.max_size') / 1024);

        $notVideoFile = UploadedFile::fake()->create('image.jpg', $maxFileSizeInKb - 1);
        $tooLargeVideoFile = UploadedFile::fake()->create('video.mp4', $maxFileSizeInKb + 1);

        foreach ([$notVideoFile, $tooLargeVideoFile] as $file) {
            $this->postJson(
                route('video_posts.create'),
                ['title' => fake()->colorName(), 'video' => $file],
            )
                ->assertStatus(422)
                ->assertJsonValidationErrorFor('video');
        }
    }
}
