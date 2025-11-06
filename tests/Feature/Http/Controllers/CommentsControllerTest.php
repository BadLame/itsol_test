<?php

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\CommentsController;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Enum\Commentables;
use App\Models\News;
use Illuminate\Support\Arr;
use Tests\TestCase;

class CommentsControllerTest extends TestCase
{
    const SINGLE_COMMENT_RESPONSE_STRUCT = [
        'data' => [
            'id',
            'is_deleted',
            'content',
            'created_at',
            'author' => ['id', 'name'],
            'answers',
        ],
    ];
    const PAGINATED_COMMENTS_RESPONSE_STRUCT = [
        'data' => [
            '*' => [
                'id',
                'is_deleted',
                'content',
                'created_at',
                'author' => ['id', 'name'],
                'answers',
            ],
        ],
        'links' => ['first', 'last', 'prev', 'next'],
        'meta' => ['path', 'per_page', 'next_cursor', 'prev_cursor'],
    ];

    // todo Добавить тест на получение комментариев для видео поста, когда появится

    // List

    function testListReturnsAnswersForComment(): void
    {
        $comment = Comment::factory()->create();
        $commentAnswers = Comment::factory(rand(1, 3))->withCommentable($comment)->create();
        $request = [
            'entity_id' => $comment->id,
            'entity_type' => Comment::class,
        ];

        $response = $this->getJson(route('comments.list', $request))
            ->assertSuccessful()
            ->assertJsonStructure(static::PAGINATED_COMMENTS_RESPONSE_STRUCT);

        $commentAnswers->each(
            fn (Comment $c) => $response->assertJsonFragment(['id' => $c->id])
        );
    }

    function testListReturnsCommentsForNews(): void
    {
        $news = News::factory()->create();
        $newsComments = Comment::factory(rand(1, 3))->withCommentable($news)->create();
        $request = [
            'entity_id' => $news->id,
            'entity_type' => News::class,
        ];

        $response = $this->getJson(route('comments.list', $request))
            ->assertSuccessful()
            ->assertJsonStructure(self::PAGINATED_COMMENTS_RESPONSE_STRUCT);

        $newsComments->each(
            fn (Comment $c) => $response->assertJsonFragment(['id' => $c->id])
        );
    }

    function testListCanTakeCommentsNestingLevelFromRequest(): void
    {
        $entity = $this->generateCommentable();
        $nestingLevel = CommentsController::DEFAULT_COMMENTS_NESTING_LEVEL + rand(1, 3);
        $request = [
            'entity_id' => $entity->id,
            'entity_type' => $entity::class,
            'comments_nesting_level' => $nestingLevel,
        ];

        // Сгенерировать цепочку вложенных комментариев
        $answers[] = Comment::factory()->withCommentable($entity)->create();
        for ($i = 1; $i <= $nestingLevel; $i++) {
            $commentToAnswer = Arr::last($answers);
            $answers[] = Comment::factory()->withCommentable($commentToAnswer)->create();
        }

        $response = $this->getJson(route('comments.list', $request))
            ->assertSuccessful();

        foreach ($answers as $ans) {
            $response->assertJsonFragment(['id' => $ans->id]);
        }
    }

    function testListPaginationIsWorking(): void
    {
        $entity = $this->generateCommentable();
        $comments = Comment::factory(CommentsController::PER_PAGE + 1)
            ->withCommentable($entity)
            ->create();
        $request = [
            'entity_id' => $entity->id,
            'entity_type' => $entity::class,
        ];
        $commentOnSecondPage = $comments[0]; // Комментарии сортируются по убыванию по id

        $response = $this->getJson(route('comments.list', $request))
            ->assertSuccessful()
            ->assertJsonMissing(['id' => $commentOnSecondPage->id]);

        $nextPageRequest = array_merge($request, [
            Comment::getCursorName() => $response->json('meta.next_cursor'),
        ]);
        $this->getJson(route('comments.list', $nextPageRequest))
            ->assertSuccessful()
            ->assertJsonFragment(['id' => $commentOnSecondPage->id]);
    }

    // Create

    function testCreateCreatesComment(): void
    {
        $commentable = $this->generateCommentable();
        $request = [
            'commentable_id' => $commentable->id,
            'commentable_type' => $commentable::class,
            'text' => fake()->text(),
        ];

        $this->postJson(route('comments.create'), $request)
            ->assertSuccessful()
            ->assertJsonStructure(static::SINGLE_COMMENT_RESPONSE_STRUCT);

        $this->assertDatabaseHas('comments', [
            'commentable_id' => $request['commentable_id'],
            'commentable_type' => $request['commentable_type'],
            'content' => $request['text'],
        ]);
    }

    // Update

    function testUpdateUpdatesComment(): void
    {
        $comment = Comment::factory()->create();
        $request = [
            'user_id' => $comment->user_id,
            'text' => fake()->text(),
        ];

        $this->patchJson(route('comments.update', $comment->id), $request)
            ->assertSuccessful()
            ->assertJsonStructure(static::SINGLE_COMMENT_RESPONSE_STRUCT);

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'content' => $request['text'],
        ]);
    }

    function testUpdateCantUpdateCommentOfAnotherUser()
    {
        $comment = Comment::factory()->create();
        $request = [
            'user_id' => $comment->user_id + 1,
            'text' => fake()->text(),
        ];

        $this->patchJson(route('comments.update', $comment->id), $request)
            ->assertForbidden();
        // Проверить, что контент не поменялся
        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'content' => $comment->content,
        ]);
    }

    // Delete

    function testDeleteDeletesComment(): void
    {
        $comment = Comment::factory()->create();
        $request = [
            'user_id' => $comment->user_id,
        ];

        $this->deleteJson(route('comments.delete', $comment->id), $request)
            ->assertSuccessful();
        $this->assertNotNull($comment->fresh()->deleted_at);
    }

    function testDeleteCantDeleteCommentOfAnotherUser()
    {
        $comment = Comment::factory()->create();
        $request = [
            'user_id' => $comment->user_id + 1,
        ];

        $this->deleteJson(route('comments.delete', $comment->id), $request)
            ->assertForbidden();
        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'deleted_at' => null,
        ]);
    }

    // Misc

    function testDeletedCommentDontShowsAuthorAndContent()
    {
        /** @var News|Comment $entity */
        $entity = $this->generateCommentable();
        $comments = Comment::factory(rand(1, 3))->withCommentable($entity)->create();
        $request = [
            'entity_id' => $entity->id,
            'entity_type' => $entity::class,
        ];

        /** @var Comment $deletedComment */
        $deletedComment = $comments->random();
        $deletedComment->update(['deleted_at' => now()]);
        $notDeletedAnswerForDeletedComment = Comment::factory()->withCommentable($deletedComment)->create();

        $this->getJson(route('comments.list', $request))
            ->assertSuccessful()
            ->assertJsonFragment([
                'id' => $deletedComment->id,
                'is_deleted' => true,
                'author' => null,
                'content' => '',
                'answers' => [
                    (new CommentResource($notDeletedAnswerForDeletedComment->load('author')))
                        ->toArray(request()),
                ],
            ]);
    }

    // Helpers

    protected function generateCommentable(): News|Comment
    {
        return Arr::random(Commentables::values())::factory()->create();
    }
}
