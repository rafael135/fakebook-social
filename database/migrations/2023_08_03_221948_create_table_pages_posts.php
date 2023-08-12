<?php

use App\Models\Page;
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
        Schema::create('pages_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Page::class, "page_id");
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
        Schema::table("pages_posts", function (Blueprint $table) {
            $table->dropForeignIdFor(Page::class, "page_id");
        });

        Schema::dropIfExists('pages_posts');
    }
};
