<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupPost extends Model
{
    use HasFactory;

    protected $table = "groups_posts";

    protected $fillable = [
        "group_id",
        "body",
        "like_count",
        "comment_count"
    ];


    public function group() {
        return $this->belongsTo(Group::class, "group_id", "id");
    }
}
