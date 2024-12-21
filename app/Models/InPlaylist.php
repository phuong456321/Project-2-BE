<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class InPlaylist extends Model
{
    use HasFactory;

    public $timestamps = false; // Tắt cả created_at và updated_at

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->created_at = now(); // Chỉ tự động set created_at
        });
    }

    protected $fillable = [
        'playlist_id',
        'song_id',
        'played_at',
    ];

    public function playlist()
    {
        return $this->belongsTo(Playlist::class);
    }

    public function song()
    {
        return $this->belongsTo(Song::class);
    }
}
