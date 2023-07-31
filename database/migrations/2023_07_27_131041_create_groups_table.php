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
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, "creator_id")->nullable(false);
            $table->string("uniqueUrl", 120)->nullable(false);
            $table->string("name", 90)->nullable(false);
            $table->json("managers")->nullable(false);
            $table->text("description")->default("");
            $table->unsignedInteger("participant_count")->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("groups", function (Blueprint $table) {
            $table->dropForeignIdFor(User::class, "creator_id");
        });

        Schema::dropIfExists('groups');
    }
};
