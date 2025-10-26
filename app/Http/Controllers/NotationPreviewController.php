<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class NotationPreviewController extends Controller
{
    public function index()
    {
        return Inertia::render('notations/Preview');
    }
}
