<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'cycles',
        'price',
        'description',
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_product')
            ->withPivot('purchased_at', 'expired_at')
            ->withTimestamps();
    }
}
