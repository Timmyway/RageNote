<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name', 'slug', 'icon_path', 'description'];

    public function moves()
    {
        return $this->belongsToMany(Move::class);
    }
}
