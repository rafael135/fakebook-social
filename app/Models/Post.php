<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $table = "posts";

    protected $fillable = [
        "user_id",
        "type",
        "body",
        "like_count",
        "comment_count"
    ];

    
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function postLike() {
        return $this->hasMany(PostLike::class, "post_id", "id");
    }
}
