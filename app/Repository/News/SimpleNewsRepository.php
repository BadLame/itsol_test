<?php

namespace App\Repository\News;

use App\Models\News;
use App\Models\Query\NewsQuery;
use Illuminate\Contracts\Pagination\CursorPaginator;

class SimpleNewsRepository implements NewsRepository
{
    function publicPaginatedList(int $perPage = 10, ?string $pageCursor = null): CursorPaginator
    {
        return $this->query()->publicList()->cursorPaginate(cursor: $pageCursor);
    }

    function publicShow(int $id): News
    {
        /** @var News $news */
        $news = $this->query()->publicDetailed()->findOrFail($id);
        return $news;
    }

    protected function query(): NewsQuery
    {
        return News::query();
    }
}
