<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    protected $fillable = ['name', 'short_name', 'image', 'notes'];

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) return null;
        return filter_var($this->image, FILTER_VALIDATE_URL) ? $this->image : asset('storage/' . $this->image);
    }

    public function videos()
    {
        return $this->hasMany(Video::class);
    }
}
