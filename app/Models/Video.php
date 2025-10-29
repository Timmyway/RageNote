<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'character_id',
        'title',
        'video_path',
        'youtube_url',
        'notation',
        'notes',
    ];

    protected $appends = ['video_url'];

    public function character()
    {
        return $this->belongsTo(Character::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'tag_video');
    }

    // Accessor for full video URL
    public function getVideoUrlAttribute(): ?string
    {
        if ($this->youtube_url) {
            return $this->youtube_url;
        }

        if ($this->video_path) {
            $url = config('app.url') . Storage::url($this->video_path);
            return $url;
        }

        return null;
    }
}
