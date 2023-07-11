<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table = "messages";

    protected $fillable = [
        "chat_id",
        "user_from",
        "user_to",
        "body"
    ];

    public function author() {
        return $this->belongsTo(User::class, "user_from", "id");
    }

    public function target() {
        return $this->hasOne(User::class, "user_to", "id");
    }
}
