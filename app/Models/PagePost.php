<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagePost extends Model
{
    use HasFactory;

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
}
