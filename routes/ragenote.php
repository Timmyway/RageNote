<?php

use App\Http\Controllers\Web\Notes\CharacterController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/characters/{id}', [CharacterController::class, 'show'])
    ->name('characters.show');
});
