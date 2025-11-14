<?php

namespace App\Services;

use App\Models\Video;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VideoService
{
    /**
     * Handles chunked video upload.
     */
    public function handleChunkUpload(
        UploadedFile $chunk,
        string $originalFilename,
        int $chunkIndex,
        int $totalChunks,
        string $uploadId
    ): ?string {
        $extension = pathinfo($originalFilename, PATHINFO_EXTENSION);
        $baseName = pathinfo($originalFilename, PATHINFO_FILENAME);
        $safeName = Str::slug($baseName) . '-' . $uploadId . '.' . $extension;

        $tmpDir = storage_path("app/tmp_videos/{$uploadId}");
        if (!file_exists($tmpDir)) {
            mkdir($tmpDir, 0777, true);
        }

        $chunkPath = "{$tmpDir}/{$chunkIndex}";
        $chunk->move($tmpDir, $chunkIndex);

        if ($chunkIndex + 1 < $totalChunks) {
            return null;
        }

        $finalDir = storage_path("app/public/videos");
        if (!file_exists($finalDir)) {
            mkdir($finalDir, 0777, true);
        }

        $finalPath = "{$finalDir}/{$safeName}";
        $out = fopen($finalPath, 'wb');
        for ($i = 0; $i < $totalChunks; $i++) {
            $part = "{$tmpDir}/{$i}";
            if (!file_exists($part)) {
                throw new \Exception("Missing chunk {$i} for upload {$uploadId}");
            }
            $in = fopen($part, 'rb');
            stream_copy_to_stream($in, $out);
            fclose($in);
        }
        fclose($out);

        array_map('unlink', glob($tmpDir . '/*'));
        rmdir($tmpDir);

        return $safeName;
    }

    public function getByCharacter(int $characterId, int $perPage = 2)
    {
        return Video::where('character_id', $characterId)
            ->paginate($perPage);
    }

    /**
     * Create a new video record.
     */
    public function create(array $data)
    {
        if (!empty($data['video_file'])) {
            $data['video_path'] = "{$data['video_file']}";
            unset($data['video_file']);
        }

        $video = Video::create($data);

        if (!empty($data['tags'])) {
            $video->tags()->sync($data['tags']);
        }

        return $video->load(['tags', 'character']);
    }

    /**
     * Update an existing video.
     */
    public function update(Video $video, array $data)
    {
        if (!empty($data['video_file'])) {
            if ($video->video_path && Storage::disk('public')->exists($video->video_path)) {
                Storage::disk('public')->delete($video->video_path);
            }

            $data['video_path'] = "{$data['video_file']}";
            unset($data['video_file']);
        }

        $video->update($data);

        if (isset($data['tags'])) {
            $video->tags()->sync($data['tags']);
        }

        return $video->load(['tags', 'character']);
    }

    /**
     * Delete a video and its file.
     */
    public function delete(Video $video)
    {
        if ($video->video_path && Storage::disk('public')->exists($video->video_path)) {
            Storage::disk('public')->delete($video->video_path);
        }

        $video->tags()->detach();
        return $video->delete();
    }
}
