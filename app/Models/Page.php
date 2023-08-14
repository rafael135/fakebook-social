<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class Page extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "pages";

    protected $fillable = [
        "creator_id",
        "uniqueUrl",
        "private",
        "name",
        "description",
        "image_url",
        "managers",
        "followers_count"
    ];



    public function getCreator() {
        return $this->belongsTo(User::class, "creator_id", "id");
    }

    public function posts() {
        return $this->hasMany(PagePost::class);
    }

    public static function convertIdsToModels(Collection $ids): Collection {
        $pageModels = collect();

        foreach($ids as $id) {
            $page = Page::find($id);

            if($page != null) {
                $pageModels->add($page);
            }
        }

        return $pageModels;
    }
}
