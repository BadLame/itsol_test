<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    function toArray(Request $request): array
    {
        /** @var User $u */
        $u = $this->resource;
        return [
            'id' => $u->id,
            'name' => $u->name,
        ];
    }
}
