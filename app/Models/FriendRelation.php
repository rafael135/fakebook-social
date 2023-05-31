<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FriendRelation extends Model
{
    use HasFactory;

    protected $table = "friends_relations";

    protected $fillable = [
        "user_from",
        "user_to"
    ];
}
