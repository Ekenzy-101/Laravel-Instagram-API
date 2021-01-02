<?php

namespace App\Models;

use App\Mail\Verification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use HasFactory, Notifiable;

    public $incrementing = false;

    protected $keyType = "string";

    protected $table = "ig_users";

    protected $fillable = [
        'id',
        'username',
        'email',
        'password',
        'gender',
        'phone_no',
        'image_url',
        'bio',
        'website',
        'name',
        'verification_code',
        'email_verified_at'
    ];

    protected $attributes = [
        'gender' => '',
        'phone_no' => '',
        'image_url' => '',
        'bio' => '',
        'website' => '',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follower_user', 'user_id', 'follower_id');
    }

    public function following(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follower_user', 'follower_id', 'user_id');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function sendEmailVerificationNotification()
    {
        Mail::to($this->email)->send(new Verification($this));
    }

    public function posts() : HasMany
    {
        return $this->hasMany(Post::class, "user_id", "id");
    }
}
