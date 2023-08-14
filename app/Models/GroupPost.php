<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class GroupPost extends Model
{
    use HasFactory;
    use SoftDeletes;

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

    public static function convertIdsToModels(Collection $ids): Collection {
        $pagePostsModels = collect();

        foreach($ids as $id) {
            $pagePost = PagePost::find($id);

            if($pagePost != null) {
                $pagePostsModels->add($pagePost);
            }
        }

        return $pagePostsModels;
    }
}
