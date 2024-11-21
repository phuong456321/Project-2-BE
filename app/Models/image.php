<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class image extends Model
{
    use HasFactory;

    protected $fillable = [
        'img_name',
        'img_path',
        'category',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'avatar_id');
    }

    public function authors()
    {
        return $this->hasMany(Author::class, 'img_id');
    }

    public function songs()
    {
        return $this->hasMany(song::class, 'img_id');
    }
}
