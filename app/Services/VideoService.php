<?php

namespace App\Services;

use App\Models\Video;
use Illuminate\Support\Facades\Storage;

class VideoService
{
    public function list($filters = [])
    {
        $query = Video::with(['tags', 'character']);

        if (!empty($filters['character_id'])) {
            $query->where('character_id', $filters['character_id']);
        }

        if (!empty($filters['tags'])) {
            $query->whereHas('tags', function ($q) use ($filters) {
                $q->whereIn('slug', $filters['tags']);
            });
        }

        return $query->get();
    }

    public function create(array $data)
    {
        // Handle uploaded video file
        if (!empty($data['video_file'])) {
            $data['video_path'] = $data['video_file']->store('videos', 'public');
            unset($data['video_file']);
        }

        $video = Video::create($data);

        if (!empty($data['tags'])) {
            $video->tags()->sync($data['tags']);
        }

        return $video->load(['tags', 'character']);
    }

    public function update(Video $video, array $data)
    {
        // Handle uploaded video file
        if (!empty($data['video_file'])) {
            // Delete old file if exists
            if ($video->video_path && Storage::disk('public')->exists($video->video_path)) {
                Storage::disk('public')->delete($video->video_path);
            }
            $data['video_path'] = $data['video_file']->store('videos', 'public');
            unset($data['video_file']);
        }

        $video->update($data);

        if (isset($data['tags'])) {
            $video->tags()->sync($data['tags']);
        }

        return $video->load(['tags', 'character']);
    }

    public function delete(Video $video)
    {
        // Delete video file if exists
        if ($video->video_path && Storage::disk('public')->exists($video->video_path)) {
            Storage::disk('public')->delete($video->video_path);
        }

        $video->tags()->detach();
        return $video->delete();
    }
}
