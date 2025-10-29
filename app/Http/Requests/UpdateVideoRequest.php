<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVideoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'character_id' => 'sometimes|exists:characters,id',
            'title' => 'sometimes|string',
            'video_file' => 'nullable|string',
            'video_path' => 'nullable|string',
            'youtube_url' => 'nullable|url',
            'notes' => 'nullable|string',
            'notation' => 'nullable|string|max:5000',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,slug',
        ];
    }
}
