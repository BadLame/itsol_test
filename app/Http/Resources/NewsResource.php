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
            'author_id' => $n->user_id,
            'author' => $this->whenLoaded('author', fn () => new UserResource($n->author)),
            'title' => $n->title,
            'content' => $n->content,
            'created_at' => $n->created_at->timestamp,
        ];
    }
}
