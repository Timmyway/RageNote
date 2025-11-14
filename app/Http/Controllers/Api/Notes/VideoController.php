<?php

namespace App\Http\Controllers\Api\Notes;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVideoRequest;
use App\Http\Requests\UpdateVideoRequest;
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

    public function store(StoreVideoRequest $request)
    {
        $data = $request->validated();

        $video = $this->service->create($data);

        return response()->json($video, 201);
    }

    public function show(Video $video)
    {
        return response()->json($video->load(['tags', 'character']));
    }

    public function update(UpdateVideoRequest $request, Video $video)
    {
        $data = $request->validated();

        $video = $this->service->update($video, $data);

        return response()->json($video);
    }

    public function byCharacter($id)
    {
        $perPage = request()->get('per_page', 2);

        $videos = $this->service->getByCharacter((int) $id, $perPage);

        return response()->json($videos);
    }

    public function destroy(Video $video)
    {
        $this->service->delete($video);
        return response()->json(null, 204);
    }
}
