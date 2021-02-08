<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Story extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $keyType = "string";

    protected $table = "ig_stories";

    protected $fillable = [
        'id',
        'user_id',
        'image_url',
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }
}
