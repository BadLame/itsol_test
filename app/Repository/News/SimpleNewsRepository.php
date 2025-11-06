<?php

namespace App\Repository\News;

use App\Models\Dto\News\CreateNewsDto;
use App\Models\News;
use App\Models\Query\NewsQuery;
use Illuminate\Contracts\Pagination\CursorPaginator;

class SimpleNewsRepository implements NewsRepository
{
    function create(CreateNewsDto $dto): News
    {
        $news = new News;
        $news->title = $dto->title;
        $news->content = $dto->content;
        $news->author()->associate($dto->user_id);
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
