<?php

namespace App\Models\Dtos\VideoPost;

use Illuminate\Http\UploadedFile;

class CreateVideoPostDto
{
    function __construct(
        public int          $userId,
        public string       $title,
        public UploadedFile $video
    )
    {
    }
}
