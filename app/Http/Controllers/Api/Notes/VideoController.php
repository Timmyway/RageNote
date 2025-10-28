<?php

namespace App\Http\Controllers\Api\Notes;

use App\Http\Controllers\Controller;
use App\Services\VideoService;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    protected $service;

    public function __construct(VideoService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['character_id', 'tags']);
        $videos = $this->service->list($filters);
        return response()->json($videos);
    }

    public function store(Request $request)
    {
        $request->validate([
            'character_id' => 'required|exists:characters,id',
            'title' => 'required|string',
            'video_file' => 'nullable|file|mimes:mp4,mov,webm|max:102400', // 100MB
            'youtube_url' => 'nullable|url',
            'notes' => 'nullable|string',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,slug',
        ]);

        if (!$request->hasFile('video_file') && empty($request->youtube_url)) {
            return response()->json(['error' => 'You must provide a video file or a YouTube link.'], 422);
        }

        $data = $request->only([
            'character_id', 'title', 'youtube_url', 'notes', 'tags'
        ]);

        if ($request->hasFile('video_file')) {
            $data['video_file'] = $request->file('video_file');
        }

        $video = $this->service->create($data);

        return response()->json($video, 201);
    }

    public function show(Video $video)
    {
        return response()->json($video->load(['tags', 'character']));
    }

    public function update(Request $request, Video $video)
    {
        $request->validate([
            'character_id' => 'sometimes|exists:characters,id',
            'title' => 'sometimes|string',
            'video_file' => 'nullable|file|mimes:mp4,mov,webm|max:102400',
            'youtube_url' => 'nullable|url',
            'notes' => 'nullable|string',
            'notation' => 'nullable|string',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,slug',
        ]);

        if (!$request->hasFile('video_file') && $request->filled('youtube_url') === false && empty($video->video_path)) {
            return response()->json(['error' => 'You must provide a video file or a YouTube link.'], 422);
        }

        $data = $request->only([
            'character_id', 'title', 'youtube_url', 'notes', 'tags', 'notation'
        ]);

        if ($request->hasFile('video_file')) {
            $data['video_file'] = $request->file('video_file');
        }

        $video = $this->service->update($video, $data);

        return response()->json($video);
    }

    public function byCharacter($id)
    {
        $videos = Video::where('character_id', $id)->get();
        return response()->json($videos);
    }

    public function destroy(Video $video)
    {
        $this->service->delete($video);
        return response()->json(null, 204);
    }
}
