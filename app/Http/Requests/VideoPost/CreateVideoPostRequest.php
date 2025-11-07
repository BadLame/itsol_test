<?php

namespace App\Http\Requests\VideoPost;

use App\Models\Dtos\VideoPost\CreateVideoPostDto;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property int|null $user_id
 * @property string $title
 */
class CreateVideoPostRequest extends FormRequest
{
    function rules(): array
    {
        $maxFilesizeInKb = (int)(config('mediable.max_size') / 1024);
        $videoExtensions = config('mediable.aggregate_types.video.extensions');

        return [
            'user_id' => 'sometimes|integer',
            'title' => 'required|string|between:3,255',
            'video' => [
                'required',
                'file',
                'extensions:' . implode(',', $videoExtensions),
                "max:$maxFilesizeInKb",
            ],
        ];
    }

    function toDto(): CreateVideoPostDto
    {
        return new CreateVideoPostDto(
            $this->user_id ?: User::factory()->create()->id,
            $this->title,
            $this->file('video'),
        );
    }
}
