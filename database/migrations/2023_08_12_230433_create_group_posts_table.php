<?php

use App\Models\Group;
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
        Schema::create('groups_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Group::class, "group_id");
            $table->text("body")->nullable(false);
            $table->integer("like_count")->default(0);
            $table->integer("comment_count")->default(0);

            $table->softDeletesDatetime();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("groups_posts", function (Blueprint $table) {
            $table->dropForeignIdFor(Group::class, "group_id");
        });

        Schema::dropIfExists('groups_posts');
    }
};
