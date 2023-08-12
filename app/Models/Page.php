<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

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
}
