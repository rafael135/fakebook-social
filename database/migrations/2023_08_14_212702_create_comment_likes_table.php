<?php

use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('comments_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Comment::class, "comment_id");
            $table->foreignIdFor(User::class, "user_id");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("comments_likes", function (Blueprint $table) {
            $table->dropForeignIdFor(Comment::class, "comment_id");
            $table->dropForeignIdFor(User::class, "user_id");
        });

        Schema::dropIfExists('comments_likes');
    }
};
