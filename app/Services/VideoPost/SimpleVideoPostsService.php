<?php

namespace App\Services\VideoPost;

use App\Models\Dtos\VideoPost\CreateVideoPostDto;
use App\Models\VideoPost;
use Plank\Mediable\Facades\MediaUploader;

class SimpleVideoPostsService extends VideoPostsService
{
    function create(CreateVideoPostDto $dto): VideoPost
    {
        $vp = new VideoPost;
        $vp->author()->associate($dto->userId);
        $vp->title = $dto->title;

        $video = MediaUploader::fromSource($dto->video)
            ->toDisk(VideoPost::VIDEO_DISK)
            ->upload();

        $this->vpRepo->create($vp, $video);

        return $vp;
    }
}
