<?php

namespace App\Repositories\VideoPost;

use App\Models\Queries\VideoPostQuery;
use App\Models\VideoPost;
use Illuminate\Pagination\CursorPaginator;

class SimpleVideoPostsRepository implements VideoPostsRepository
{
    function publicPaginatedList(int $perPage = 10): CursorPaginator
    {
        return $this->query()
            ->publicList()
            ->cursorPaginate($perPage);
    }

    protected function query(): VideoPostQuery
    {
        return VideoPost::query();
    }
}
