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
        return $this->belongsTo(Image::class, 'img_id', 'img_id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }
    public function user()
{
    return $this->belongsTo(User::class, 'author_id', 'id');
}

    // Mối quan hệ giữa Author và Song
    public function songs()
{
    return $this->hasMany(Song::class, 'author_id', 'id');
}
}
