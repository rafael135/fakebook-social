<?php

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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, "creator_id");
            $table->string("uniqueUrl", 120)->nullable(false);
            $table->boolean("private")->default(false);
            $table->string("name", 90)->nullable(false);
            $table->text("description")->default("");
            $table->string("image_url", 120)->default("");
            $table->json("managers")->nullable(true)->default(null);
            $table->unsignedInteger("followers_count")->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("pages", function (Blueprint $table) {
            $table->dropForeignIdFor(User::class, "creator_id");
        });

        Schema::dropIfExists('pages');
    }
};
