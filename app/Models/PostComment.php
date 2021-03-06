<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PostComment extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $keyType = "string";

    protected $table = "ig_post_comments";

    protected $fillable = [
        'id',
        'content',
        'post_id',
        'user_id',
    ];

    public function replies() : HasMany {
        return $this->hasMany(ReplyComment::class, "comment_id", "id");
    }

    public function post() : BelongsTo
    {
        return $this->belongsTo(Post::class, "post_id", "id");
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }

    public function likes() : BelongsToMany
    {
        return $this->belongsToMany(User::class, "ig_comment_likes", "comment_id", "user_id");
    }
}
