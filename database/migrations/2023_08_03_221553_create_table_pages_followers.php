<?php

use App\Models\Page;
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
        Schema::create('pages_followers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Page::class, "page_id");
            $table->foreignIdFor(User::class, "user_id");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("pages_followers", function(Blueprint $table) {
            $table->dropForeignIdFor(Page::class, "page_id");
            $table->dropForeignIdFor(User::class, "user_id");
        });

        Schema::dropIfExists('pages_followers');
    }
};
