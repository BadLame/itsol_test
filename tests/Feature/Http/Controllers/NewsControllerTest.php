<?php

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\NewsController;
use App\Http\Resources\UserResource;
use App\Models\Comment;
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

    function testShowDisplaysNewsWithCommentsAndAnswers()
    {
        $news = News::factory()->withComments()->create();
        /** @var Comment $randComment */
        $randComment = $news->comments->random();
        $randCommentAns = Comment::factory()->withCommentable($randComment)->create();

        $this->getJson(route('news.show', $news))
            ->assertSuccessful()
            ->assertJsonStructure([
                'data' => [
                    'comments' => [
                        '*' => [
                            'id',
                            'author',
                            'answers',
                            'content',
                            'created_at',
                        ],
                    ],
                ],
            ])
            ->assertJsonFragment([
                'id' => $randComment->id,
                'answers' => [
                    [
                        'id' => $randCommentAns->id,
                        'content' => $randCommentAns->content,
                        'created_at' => $randCommentAns->created_at->timestamp,
                        'author' => (new UserResource($randCommentAns->author))->toArray(request()),
                        'answers' => [],
                    ],
                ],
            ]);
    }
}
