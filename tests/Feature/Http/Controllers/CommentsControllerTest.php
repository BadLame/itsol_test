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
    const COMMENTS_RESPONSE_STRUCT = [
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
            ->assertJsonStructure(static::COMMENTS_RESPONSE_STRUCT);

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
            ->assertJsonStructure(self::COMMENTS_RESPONSE_STRUCT);

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
        $comments = Comment::factory(CommentsController::PER_PAGE + 1)->withCommentable($entity)->create();
        $request = [
            'entity_id' => $entity->id,
            'entity_type' => $entity::class,
        ];
        $commentOnSecondPage = $comments[0]; // Комментарии сортируются по убыванию по id

        $response = $this->getJson(route('comments.list', $request))
            ->assertSuccessful()
            ->assertJsonMissing(['id' => $commentOnSecondPage->id]);

        $nextPageRequest = array_merge($request, [
            $entity::getCursorName() => $response->json('meta.next_cursor'),
        ]);
        $this->getJson(route('comments.list', $nextPageRequest))
            ->assertSuccessful()
            ->assertJsonFragment(['id' => $commentOnSecondPage->id]);
    }

    function testListReturnsNotFoundForNonExistingEntity(): void
    {
        $entity = $this->generateCommentable();
        $request = [
            'entity_id' => $entity::query()->max('id') + 1, // отправить несуществующий ID
            'entity_type' => $entity::class,
        ];

        $this->getJson(route('comments.list', $request))
            ->assertNotFound();
    }

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

    protected function generateCommentable(): News|Comment
    {
        return Arr::random(Commentables::values())::factory()->create();
    }
}
