<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Notes\VideoController;
use App\Http\Controllers\Api\Notes\CharacterController;
use App\Http\Controllers\Api\Notes\TagController;
use App\Http\Controllers\Api\Videos\UploadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::get('/characters', [CharacterController::class, 'index']);
    Route::get('/characters/{id}', [CharacterController::class, 'show']);
    Route::get('/tags', [TagController::class, 'index']);

    Route::get('/characters/{id}/videos', [VideoController::class, 'byCharacter']);
    Route::get('videos', [VideoController::class, 'index']);
    Route::get('videos/{video}', [VideoController::class, 'show']);

    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('web')->group(function () {
        Route::post('videos', [VideoController::class, 'store']);
        Route::put('videos/{video}', [VideoController::class, 'update']);
        Route::patch('videos/{video}', [VideoController::class, 'update']);
        Route::delete('videos/{video}', [VideoController::class, 'destroy']);

        Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);

        // Chunked upload route
        Route::post('videos/upload-chunk', [UploadController::class, 'uploadChunk']);
    });
});
