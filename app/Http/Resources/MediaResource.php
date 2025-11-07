<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Plank\Mediable\Media;

class MediaResource extends JsonResource
{
    function toArray(Request $request): array
    {
        /** @var Media $vid */
        $vid = $this->resource;

        return [
            'alt' => $vid->getBasenameAttribute(),
            'url' => $vid->getUrl(),
        ];
    }
}
