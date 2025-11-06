<?php

namespace App\Repositories\News;

use App\Models\News;
use App\Models\Queries\NewsQuery;
use Illuminate\Contracts\Pagination\CursorPaginator;

class SimpleNewsRepository implements NewsRepository
{
    function create(News $news): News
    {
        $news->save();

        return $news;
    }

    function publicPaginatedList(int $perPage = 10): CursorPaginator
    {
        return $this->query()
            ->publicList()
            ->cursorPaginate(perPage: $perPage, cursorName: News::getCursorName());
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
