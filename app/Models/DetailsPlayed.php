<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class DetailsPlayed extends Model
{
    use HasFactory;

    protected $fillable = [
        'recently_id',
        'song_id',
    ];

    public function recentlyPlayed()
    {
        return $this->belongsTo(RecentlyPlayed::class, 'recently_id');
    }

    public function song()
    {
        return $this->belongsTo(Song::class);
    }
}
