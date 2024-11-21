<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Author extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_name',
        'bio',
        'img_id',
        'user_id',
        'area_id',
    ];

    public function image()
    {
        return $this->belongsTo(image::class, 'img_id');
    }

    public function area()
    {
        return $this->belongsTo(area::class, 'area_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function songs()
    {
        return $this->hasMany(song::class);
    }
}
