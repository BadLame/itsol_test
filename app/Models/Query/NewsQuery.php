<?php

namespace App\Models\Query;

use App\Models\News;
use Illuminate\Database\Eloquent\Builder;

/** @mixin News */
class NewsQuery extends Builder
{
    function forPublicViewers(): static
    {
        return $this->orderByDesc('news.id')->with(['author']);
    }
}
