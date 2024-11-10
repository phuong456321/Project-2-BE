<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class music extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'author_id',
        'category_id',
        'area_id',
        'description',
        'img_id',
        'audio_path',
        'status',
    ];

    public function imgs(){
        return $this->hasMany(img::class);
    }

    public function authors(){
        return $this->belongsTo(author::class);
    }

    public function categories(){
        return $this->belongsTo(category::class);
    }

    public function areas(){
        return $this->belongsTo(area::class);
    }

}
