<?php

namespace App\Http\Controllers\Api\Videos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\VideoService;

class UploadController extends Controller
{
    protected VideoService $service;

    public function __construct(VideoService $service)
    {
        $this->service = $service;
    }

    public function uploadChunk(Request $request)
    {
        $request->validate([
            'chunkIndex' => 'required|integer|min:0',
            'totalChunks' => 'required|integer|min:1',
            'filename' => 'required|string',
            'file' => 'required|file',
            'uploadId' => 'nullable|string',
        ]);

        // Use given uploadId or generate one (for the first chunk)
        $uploadId = $request->input('uploadId') ?? (string) \Illuminate\Support\Str::uuid();

        $filename = $this->service->handleChunkUpload(
            $request->file('file'),
            $request->input('filename'),
            (int)$request->input('chunkIndex'),
            (int)$request->input('totalChunks'),
            $uploadId
        );

        return response()->json([
            'success' => true,
            'uploadId' => $uploadId,
            'filename' => $filename,
            'video_path' => $filename ? "videos/{$filename}" : null,
        ]);
    }
}
