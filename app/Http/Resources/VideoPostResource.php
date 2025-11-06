<?php

namespace App\Http\Resources;

use App\Models\VideoPost;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoPostResource extends JsonResource
{
    function toArray(Request $request): array
    {
        /** @var VideoPost $vp */
        $vp = $this->resource;

        return [
            'id' => $vp->id,
            'author_id' => $vp->user_id,
            'title' => $vp->title,
            'created_at' => $vp->created_at->timestamp,

            'author' => $this->whenLoaded(
                'author',
                fn () => $vp->author ? new UserResource($vp->author) : null,
                null
            ),
            'video' => $this->whenLoaded(
                'video',
                fn () => new MediaResource($vp->video->first()),
                null
            ),
        ];
    }
}
