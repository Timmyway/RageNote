<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    protected $fillable = ['name', 'short_name', 'image', 'notes'];

    public function moves()
    {
        return $this->hasMany(Move::class);
    }
}
