<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'song_id',
        'user_id',
        'cmt',
    ];

    public function song()
    {
        return $this->belongsTo(song::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
