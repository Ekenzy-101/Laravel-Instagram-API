<?php

namespace App\Models;

use App\Mail\PasswordConfirmation;
use App\Mail\PasswordReset;
use App\Mail\Verification;
use Illuminate\Auth\Passwords\CanResetPassword;
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
    use HasFactory, Notifiable, CanResetPassword;

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
        'object_key',
        'verification_code',
        'email_verified_at'
    ];

    protected $attributes = [
        'gender' => '',
        'phone_no' => '',
        'image_url' => '',
        'bio' => '',
        'website' => '',
        'password' => '',
        'object_key' => '',
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
        return $this->belongsToMany(User::class, 'ig_user_follows', 'user_id', 'follower_id');
    }

    public function following(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'ig_user_follows', 'follower_id', 'user_id');
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

    public function sendPasswordResetNotification($token)
    {
        $endpoint = getenv("FRONTEND_ENDPOINT");
        $email = $this->email;
        $username = $this->username;
        $link = "{$endpoint}/accounts/password/reset/confirm/?token={$token}";

        $data = compact("username", "email", "link");

        Mail::to($email)->send(new PasswordReset($data));
    }

    public function sendPasswordConfirmationNotification()
    {
        $email = $this->email;
        $username = $this->username;

        $data = compact("username", "email");

        Mail::to($email)->send(new PasswordConfirmation($data));
    }

    public function posts() : HasMany
    {
        return $this->hasMany(Post::class, "user_id", "id");
    }

    public function stories() : HasMany
    {
        return $this->hasMany(Story::class, "user_id", "id");
    }

    public function likedPosts() : BelongsToMany
    {
        return $this->belongsToMany(Post::class, "ig_post_likes", "user_id", "post_id");
    }

    public function savedPosts() : BelongsToMany
    {
        return $this->belongsToMany(Post::class, "ig_post_saves", "user_id", "post_id");
    }

    public function likedComments() : BelongsToMany
    {
        return $this->belongsToMany(PostComment::class, "ig_comment_likes", "user_id", "comment_id");
    }

    public function likedReplies() : BelongsToMany
    {
        return $this->belongsToMany(ReplyComment::class, "ig_reply_likes", "user_id", "reply_id");
    }
}
