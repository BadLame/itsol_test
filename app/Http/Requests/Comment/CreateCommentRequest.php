<?php

namespace App\Http\Requests\Comment;

use App\Models\Comment;
use App\Models\Dto\Comment\CreateCommentDto;
use App\Models\Enum\Commentables;
use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property int $user_id
 * @property string $text
 * @property int $commentable_id
 * @property class-string<News|Comment> $commentable_type
 */
class CreateCommentRequest extends FormRequest
{
    function rules(): array
    {
        return [
            /** Если не передан - будет использован случайный */
            'user_id' => 'sometimes|int',
            'text' => 'required|string|min:3',
            'commentable_id' => 'required|int',
            'commentable_type' => ['required', 'string', Rule::enum(Commentables::class)],
        ];
    }

    function after(): array
    {
        return [
            // Для возможности не отправлять user_id
            fn () => $this->merge(['user_id' => $this->user_id ?: User::query()->inRandomOrder()->value('id')]),
        ];
    }

    function toDto(): CreateCommentDto
    {
        return new CreateCommentDto(
            $this->commentable_id,
            $this->commentable_type,
            $this->user_id,
            $this->text
        );
    }
}
