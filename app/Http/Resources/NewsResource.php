<?php

namespace App\Http\Resources;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{
    function toArray(Request $request): array
    {
        /** @var News $n */
        $n = $this->resource;

        return [
            'id' => $n->id,
            'author_id' => (int)$n->user_id,
            'title' => $n->title,
            'content' => $n->content,
            'created_at' => $n->created_at->timestamp,

            'author' => $this->whenLoaded(
                'author',
                fn () => $n->author ? new UserResource($n->author) : null,
                null
            ),
        ];
    }
}
