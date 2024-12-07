<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class RecentlyPlayed extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['user_id'];

    public function details()
    {
        return $this->hasMany(DetailsPlayed::class, 'recently_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
