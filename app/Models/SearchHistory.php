<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class SearchHistory extends Model
{
    use HasFactory;
    protected $table = 'search_history'; 
    protected $fillable = [
        'user_id',
        'content',
        'clicked_song_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
