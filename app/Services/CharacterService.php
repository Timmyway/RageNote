<?php

namespace App\Services;

use App\Models\Character;

class CharacterService
{
    public function all()
    {
        return Character::all();
    }

    public function find($id)
    {
        return Character::with('moves.tags')->findOrFail($id);
    }
}
