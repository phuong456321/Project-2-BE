<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Song extends Model
{
    use HasFactory;

    protected $fillable = [
        'song_name',
        'author_id',
        'area_id',
        'genre_id',
        'description',
        'audio_path',
        'img_id',
        'status',
        'likes',
        'play_count',
        'lyric',
        'waveform_path',
        'lyric_path',
    ];

    // Mối quan hệ giữa Song và Author
    public function author()
{
    return $this->belongsTo(Author::class, 'author_id', 'id');
}


    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    public function image()
    {
        return $this->belongsTo(Image::class, 'img_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function inPlaylists()
    {
        return $this->hasMany(InPlaylist::class);
    }
}
