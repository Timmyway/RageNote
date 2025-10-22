<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'character_id',
        'title',
        'video_path',
        'youtube_url',
        'notes',
    ];

    public function character()
    {
        return $this->belongsTo(Character::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'tag_video');
    }
}
