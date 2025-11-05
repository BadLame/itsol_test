<?php

namespace App\Repository\News;

use App\Models\News;
use App\Models\Query\NewsQuery;
use Illuminate\Contracts\Pagination\CursorPaginator;

class SimpleNewsRepository implements NewsRepository
{
    function publicPaginatedList(int $perPage = 10, ?string $pageCursor = null): CursorPaginator
    {
        return $this->query()->forPublicViewers()->cursorPaginate(cursor: $pageCursor);
    }

    protected function query(): NewsQuery
    {
        return News::query();
    }
}
