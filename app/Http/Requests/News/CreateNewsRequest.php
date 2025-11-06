<?php

namespace App\Http\Requests\News;

use App\Models\Dtos\News\CreateNewsDto;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property int $user_id
 * @property string $title
 * @property string $text
 */
class CreateNewsRequest extends FormRequest
{
    function rules(): array
    {
        return [
            /** Если не передан - будет взят случайный из существующих */
            'user_id' => 'sometimes|integer',
            'title' => 'required|string|between:3,255',
            'text' => 'required|string|min:3',
        ];
    }

    function after(): array
    {
        return [
            fn () => $this->merge(['user_id' => $this->user_id ?: User::query()->inRandomOrder()->value('id')]),
        ];
    }

    function toDto(): CreateNewsDto
    {
        return new CreateNewsDto(
            $this->title,
            $this->text,
            $this->user_id
        );
    }
}
