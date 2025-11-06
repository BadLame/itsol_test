<?php

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property int $user_id
 */
class DeleteCommentRequest extends FormRequest
{
    function rules(): array
    {
        return [
            /**
             * ID "авторизованного" пользователя.\
             * Используется в проверке на возможность удаления (нельзя удалить чужой)
             */
            'user_id' => 'required|integer',
        ];
    }
}
