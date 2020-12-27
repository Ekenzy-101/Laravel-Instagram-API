<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $keyType = "string";

    protected $table = "ig_posts";

    protected $fillable = [
        'id',
        'caption',
        'image_urls',
        'user_id',
    ];

    protected $casts = [
        'image_urls' => 'array',
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }

    public function comments() : HasMany
    {
        return $this->hasMany(PostComment::class, "post_id", "id");
    }
}
