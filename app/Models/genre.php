<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Genre extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'name',
        'parents_id',
    ];

    public function parents()
    {
        return $this->belongsTo(Genre::class, 'parents_id');
    }

    public function songs()
    {
        return $this->hasMany(Song::class);
    }
}
