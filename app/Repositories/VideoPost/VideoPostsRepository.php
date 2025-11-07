<?php

namespace App\Repositories\VideoPost;

use App\Models\VideoPost;
use Illuminate\Pagination\CursorPaginator;
use Plank\Mediable\Media;

interface VideoPostsRepository
{
    /**
     * Получить список видео-постов для просмотра пользователем
     *
     * @param int $perPage
     * @return CursorPaginator<VideoPost>
     */
    function publicPaginatedList(int $perPage = 10): CursorPaginator;

    /** Привязать отношения к модели */
    function loadRelations(VideoPost &$videoPost, array $relations): VideoPost;

    /** Сохранить новую запись видео-поста */
    function create(VideoPost &$videoPost, Media $video): VideoPost;
}
