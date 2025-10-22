<?php

namespace App\Services;

use App\Models\Character;

class CharacterService
{
    public function all()
    {
        // Return all characters with full image URL
        return Character::all()->map(function ($character) {
            return [
                'id' => $character->id,
                'name' => $character->name,
                'short_name' => $character->short_name,
                'notes' => $character->notes,
                'image' => $character->image_url, // accessor provides full URL
            ];
        });
    }

    public function find($id)
    {
        $character = Character::with('videos.tags')->findOrFail($id);

        return [
            'id' => $character->id,
            'name' => $character->name,
            'short_name' => $character->short_name,
            'notes' => $character->notes,
            'image' => $character->image_url,
            'videos' => $character->videos->map(function ($video) {
                return [
                    'id' => $video->id,
                    'title' => $video->title,
                    'notes' => $video->notes,
                    'tags' => $video->tags,
                    'video_file' => $video->video_file_url, // accessor for full URL
                    'youtube_url' => $video->youtube_url,
                ];
            }),
        ];
    }
}
