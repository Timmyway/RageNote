<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Move extends Model
{
    protected $fillable = [
        'character_id', 'name', 'input_raw', 'input_clean',
        'startup', 'on_block', 'on_hit', 'notes'
    ];

    public function character()
    {
        return $this->belongsTo(Character::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
