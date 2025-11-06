<?php

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\VideoPostsController;
use App\Models\VideoPost;
use Tests\TestCase;

class VideoPostsControllerTest extends TestCase
{
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
}
