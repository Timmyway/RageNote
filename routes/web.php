<?php

use App\Http\Controllers\NotationPreviewController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('notation', [NotationPreviewController::class, 'index'])->name('notation');

require __DIR__.'/settings.php';
require __DIR__.'/ragenote.php';
