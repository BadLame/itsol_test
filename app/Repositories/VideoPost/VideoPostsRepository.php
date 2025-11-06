<?php

namespace App\Repositories\VideoPost;

use App\Models\VideoPost;
use Illuminate\Pagination\CursorPaginator;

interface VideoPostsRepository
{
    /**
     * Получить список видео-постов для просмотра пользователем
     *
     * @param int $perPage
     * @return CursorPaginator<VideoPost>
     */
    function publicPaginatedList(int $perPage = 10): CursorPaginator;
}
