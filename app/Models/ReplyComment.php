<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ReplyComment extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $keyType = "string";

    protected $table = "ig_reply_comments";

    protected $fillable = [
        'id',
        'content',
        'post_id',
        'comment_id',
        'user_id',
    ];

    public function comment() : BelongsTo
    {
        return $this->belongsTo(PostComment::class, "comment_id", "id", "replies");
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
        return $this->belongsToMany(User::class, "ig_reply_likes", "reply_id", "user_id");
    }
}
