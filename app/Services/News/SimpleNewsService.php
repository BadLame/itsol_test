<?php

namespace App\Services\News;

use App\Models\Dtos\News\CreateNewsDto;
use App\Models\News;

class SimpleNewsService extends NewsService
{
    function create(CreateNewsDto $dto): News
    {
        $n = new News;
        $n->title = $dto->title;
        $n->content = $dto->content;
        $n->author()->associate($dto->userId);

        return $this->newsRepo->create($n);
    }
}
