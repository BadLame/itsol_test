<?php

namespace App\Repositories\VideoPost;

use App\Models\Queries\VideoPostQuery;
use App\Models\VideoPost;
use Illuminate\Pagination\CursorPaginator;
use Plank\Mediable\Media;

class SimpleVideoPostsRepository implements VideoPostsRepository
{
    function publicPaginatedList(int $perPage = 10): CursorPaginator
    {
        return $this->query()
            ->publicList()
            ->cursorPaginate($perPage);
    }

    function loadRelations(VideoPost &$videoPost, array $relations): VideoPost
    {
        return $videoPost->load($relations);
    }

    function create(VideoPost &$videoPost, Media $video): VideoPost
    {
        $videoPost->save();
        $videoPost->attachMedia($video, VideoPost::VIDEO_TAG);

        return $videoPost;
    }

    protected function query(): VideoPostQuery
    {
        return VideoPost::query();
    }
}
