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
        'avatar_id',
        'email_verification_token',
        'email_verification_sent_at',
    ];

    protected $casts = [
        'email_verification_sent_at' => 'datetime',
    ];


    public function avatar()
    {
        return $this->belongsTo(Image::class, 'avatar_id', 'img_id');
    }    


    public function googleAccount()
    {
        return $this->hasOne(GoogleAccount::class, 'google_id');
    }

    public function playlists()
    {
        return $this->hasMany(Playlist::class);
    }

    public function recentlyPlayed()
    {
        return $this->hasMany(RecentlyPlayed::class);
    }

    public function searchHistories()
    {
        return $this->hasMany(SearchHistory::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Mối quan hệ giữa User và Author
    public function author()
    {
        return $this->hasOne(Author::class, 'id', 'author_id');
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'user_product')
            ->withPivot('purchased_at', 'expired_at') // Thêm thông tin từ bảng pivot nếu cần
            ->withTimestamps(); // Nếu muốn lấy thông tin thời gian
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


    // *Trigger here*
    protected static function booted()
    {
        static::created(function ($user) {
            // Tạo một playlist mặc định khi user mới được tạo
            \App\Models\Playlist::create([
                'user_id' => $user->id,
                'name' => 'Liked music',
            ]);
        });

        //Tạo author mới khi user mới được tạo (nếu user có role là 'user')
        static::created(function ($user) {
            if ($user->role === 'user') {
                $author = \App\Models\Author::create([
                    'author_name' => $user->name,        // Lấy tên User làm tên Author
                    'img_id' => $user->avatar_id,       // Lấy avatar của User
                    'area_id' => 1,                     // Giá trị mặc định cho area_id
                    'bio' => 'New author',              // Giá trị mặc định cho bio
                ]);

                $user->author_id = $author->id;
                $user->save();
            }
        });
    }
}
