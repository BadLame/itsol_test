<?php

namespace App\Models\Query;

use App\Models\News;
use Illuminate\Database\Eloquent\Builder;

/** @mixin News */
class NewsQuery extends Builder
{
    /** Подгрузить подробную информацию для просмотра пользователем */
    function publicDetailed(): static
    {
        // Осталось из-за изначального плана подгружать тут комменты
        // но в дальнейшем может использоваться для подгрузки доп данных, которые не выводятся в списке
        return $this->with(['author']);
    }

    /** Упорядочить и подгрузить отношения для просмотра пользователями в списке */
    function publicList(): static
    {
        return $this->orderByDesc('news.id')->with(['author']);
    }
}
