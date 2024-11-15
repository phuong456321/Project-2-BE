<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class genre extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'parents_id',
    ];

    public function parents()
    {
        return $this->belongsTo(genre::class, 'parents_id');
    }

    public function songs()
    {
        return $this->hasMany(song::class);
    }
}
