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
 */
class CommentsListRequest extends FormRequest
{
    public ?int $comments_nesting_level;
    public News|Comment|null $entity;

    function rules(): array
    {
        return [
            'entity_id' => 'required|integer',
            'entity_type' => ['required', 'string', Rule::enum(Commentables::class)],
            /** До какого уровня вложенности прогружать комментарии */
            'comments_nesting_level' => 'sometimes|integer|min:1',
        ];
    }

    function after(): array
    {
        return [
            function () {
                if (in_array($this->entity_type, Commentables::values()) && $this->entity_id) {
                    $this->entity = $this->entity_type::query()->findOrFail($this->entity_id);
                }
            },
            fn () => $this->comments_nesting_level = $this->input('comments_nesting_level'),
        ];
    }
}
