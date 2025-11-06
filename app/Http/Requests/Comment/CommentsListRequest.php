<?php

namespace App\Http\Requests\Comment;

use App\Models\Comment;
use App\Models\Enum\Commentables;
use App\Models\News;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property int $entity_id
 * @property class-string<News|Comment> $entity_type
 * @property int|null $comments_nesting_level
 */
class CommentsListRequest extends FormRequest
{
    function rules(): array
    {
        return [
            'entity_id' => 'required|integer',
            'entity_type' => ['required', 'string', Rule::enum(Commentables::class)],
            /** До какого уровня вложенности прогружать комментарии */
            'comments_nesting_level' => 'sometimes|integer|min:1',
        ];
    }
}
