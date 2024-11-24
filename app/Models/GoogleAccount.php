<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GoogleAccount extends Model
{
    use HasFactory;
    protected $fillable = ['google_id', 'email', 'name', 'avatar_id'];
    public function image()
    {
        return $this->belongsTo(Image::class, 'avatar_id');
    }
    public function user()
    {
        return $this->hasOne(User::class, 'google_id');
    }
}
