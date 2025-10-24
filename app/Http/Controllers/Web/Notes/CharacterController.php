<?php

namespace App\Http\Controllers\Web\Notes;

use App\Http\Controllers\Controller;
use App\Services\CharacterService;
use Inertia\Inertia;

class CharacterController extends Controller
{
    public function __construct(protected CharacterService $service) {}

    public function show($id)
    {
        $character = $this->service->find($id);

        return Inertia::render('characters/CharacterShow', [
            'character' => $character,
            'id' => $id, // ✅ Pass the ID explicitly
        ]);
    }
}
