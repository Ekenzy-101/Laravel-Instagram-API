<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
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
    ];

    protected $attributes = [
        'gender' => '',
        'phone_no' => '',
        'image_url' => '',
        'bio' => '',
        'website' => '',
        'name' => '',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function posts() : HasMany
    {
        return $this->hasMany(Post::class, "user_id", "id");
    }
}
