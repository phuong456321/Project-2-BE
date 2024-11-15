<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class in_playlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'playlist_id',
        'song_id',
        'played_at',
    ];

    public function playlist()
    {
        return $this->belongsTo(playlist::class);
    }

    public function song()
    {
        return $this->belongsTo(song::class);
    }
}
