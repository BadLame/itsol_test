<?php

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property int $user_id
 * @property string $text
 */
class UpdateCommentRequest extends FormRequest
{
    function rules(): array
    {
        return [
            /** ID "авторизованного" пользователя для проверки на возможность обновить комментарий */
            'user_id' => 'required|integer',
            'text' => 'required|string|min:3',
        ];
    }
}
