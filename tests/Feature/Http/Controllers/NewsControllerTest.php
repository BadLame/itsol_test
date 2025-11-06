<?php

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\NewsController;
use App\Http\Resources\NewsResource;
use App\Models\News;
use App\Models\User;
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
        $this->getJson(route('news.list', [
            News::getCursorName() => $response->json('meta.next_cursor'),
        ]))
            ->assertSuccessful()
            ->assertJsonFragment([
                'id' => $shouldBeOnSecondPage->id,
                'title' => $shouldBeOnSecondPage->title,
            ]);
    }

    function testShowDisplaysNews()
    {
        $news = News::factory()->withComments(NewsController::PER_PAGE + 1)->create();

        $this->getJson(route('news.show', $news))
            ->assertSuccessful()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'author_id',
                    'author' => ['id', 'name'],
                    'title',
                    'content',
                    'created_at',
                ],
            ]);
    }

    function testCreateCreatesNews()
    {
        $request = [
            'user_id' => User::factory()->create()->id,
            'title' => fake()->colorName(),
            'text' => fake()->text(),
        ];

        $response = $this->postJson(route('news.create'), $request)
            ->assertSuccessful();
        $news = News::find($response->json('data.id'));

        $response->assertJsonFragment(json_decode((new NewsResource($news))->toJson(), true));
        $this->assertDatabaseHas('news', [
            'user_id' => $request['user_id'],
            'title' => $request['title'],
            'content' => $request['text'],
        ]);
    }
}
