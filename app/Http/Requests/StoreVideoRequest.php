<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVideoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'character_id' => 'required|exists:characters,id',
            'title' => 'required|string',
            'video_file' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) {
                    $path = storage_path('app/videos/' . $value);
                    if (!file_exists($path)) {
                        $fail('The uploaded video file could not be found.');
                    }
                },
            ],
            'video_path' => 'nullable|string', // for chunk uploads
            'youtube_url' => 'nullable|url',
            'notes' => 'nullable|string',
            'notation' => 'nullable|string|max:5000',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,slug',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (
                !$this->hasFile('video_file')
                && empty($this->video_path)
                && empty($this->youtube_url)
            ) {
                $validator->errors()->add('video_file', 'You must provide a video file or a YouTube link.');
            }
        });
    }
}
