<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Area extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'name',
        'parents_id',
    ];

    public function parents()
    {
        return $this->belongsTo(Area::class, 'parents_id');
    }

    public function authors()
    {
        return $this->hasMany(Author::class);
    }

    public function songs()
    {
        return $this->hasMany(Song::class);
    }
}
