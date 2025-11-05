<?php

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\NewsController;
use App\Models\News;
use Tests\TestCase;

class NewsControllerTest extends TestCase
{
    function testListReturnsPaginatedNews(): void
    {
        $news = News::factory(NewsController::PER_PAGE + 1)->create();
        /** @var News $shouldBeOnSecondPage */
        $shouldBeOnSecondPage = $news->sortBy('id')->first();

        $response = $this->getJson(route('news.list'))
            ->assertSuccessful()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'author_id',
                        'author' => ['id', 'name'],
                        'title',
                        'content',
                        'created_at',
                    ],
                ],
                'links' => ['first', 'last', 'prev', 'next'],
                'meta' => ['path', 'per_page', 'next_cursor', 'prev_cursor'],
            ]);

        // Проверить работу пагинации
        $this->getJson(route('news.list', ['cursor' => $response->json('meta.next_cursor')]))
            ->assertSuccessful()
            ->assertJsonFragment([
                'id' => $shouldBeOnSecondPage->id,
                'title' => $shouldBeOnSecondPage->title,
            ]);
    }
}
