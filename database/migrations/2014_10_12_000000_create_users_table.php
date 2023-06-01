<?php

use Carbon\Carbon;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('email', 150)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 200);

            $table->string("avatar", 255)->nullable();
            $table->string("cover", 255)->nullable();
            $table->string("city", 80)->nullable();
            $table->string("state", 80)->nullable();
            $table->string("country", 80)->nullable();

            $table->string("birth_date", 10)->nullable();
            $table->integer("friend_count")->default(0);

            $table->timestamp('last_online_at')->nullable();
            $table->rememberToken();
            $table->softDeletesDatetime();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
