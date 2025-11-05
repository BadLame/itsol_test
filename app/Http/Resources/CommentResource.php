<?php

namespace App\Http\Resources;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    function toArray(Request $request): array
    {
        /** @var Comment $c */
        $c = $this->resource;
        return [
            'id' => $c->id,
            'content' => $c->content,
            'created_at' => $c->created_at->timestamp,

            'author' => $this->whenLoaded(
                'author',
                fn () => $c->author ? new UserResource($c->author) : null, // Пользователь может быть удалён
                null
            ),
            'answers' => $this->whenLoaded(
                'answers',
                fn () => CommentResource::collection($c->answers),
                []
            ),
        ];
    }
}
