<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

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

    public static function convertIdsToModels(Collection $ids): Collection {
        $postModels = collect();

        foreach($ids as $id) {
            $post = Post::find($id);

            if($post != null) {
                $postModels->add($post);
            }
        }

        return $postModels;
    }
}
