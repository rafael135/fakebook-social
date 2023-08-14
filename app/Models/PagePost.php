<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class PagePost extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "pages_posts";

    protected $fillable = [
        "page_id",
        "body",
        "like_count",
        "comment_count"
    ];



    public function page() {
        return $this->belongsTo(Page::class, "page_id", "id");
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
