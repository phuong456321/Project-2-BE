<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class area extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'parents_id',
    ];

    public function parents()
    {
        return $this->belongsTo(area::class, 'parents_id');
    }

    public function authors()
    {
        return $this->hasMany(Author::class);
    }

    public function songs()
    {
        return $this->hasMany(song::class);
    }
}
