<?php

namespace App\Services;

use App\Models\Tag;

class TagService
{
    public function all()
    {
        return Tag::all();
    }
}
