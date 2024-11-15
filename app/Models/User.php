<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmailContract
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'plan',
        'status',
        'google_id',
        'avatar_id'
    ];

    public function avatar()
    {
        return $this->belongsTo(image::class, 'avatar_id');
    }

    public function playlists()
    {
        return $this->hasMany(playlist::class);
    }

    public function recentlyPlayed()
    {
        return $this->hasMany(recently_played::class);
    }

    public function searchHistories()
    {
        return $this->hasMany(search_history::class);
    }

    public function comments()
    {
        return $this->hasMany(comment::class);
    }

    public function payments()
    {
        return $this->hasMany(payment::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
