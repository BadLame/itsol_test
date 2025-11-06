<?php

namespace App\Models\Queries;

use App\Models\VideoPost;
use Illuminate\Database\Eloquent\Builder;

/** @mixin VideoPost */
class VideoPostQuery extends Builder
{
    /** Упорядочить и подгрузить отношения для просмотра пользователем */
    function publicList(): static
    {
        return $this->orderByDesc('video_posts.id')->with(['author']);
    }
}
