<?php

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
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->unsignedBigInteger('author_id')->nullable();
            $table->enum('role', ['admin', 'user'])->default('user');
            $table->enum('plan', ['free', 'premium'])->default('free');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('google_id')->nullable();
            $table->unsignedBigInteger('avatar_id')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->foreign('avatar_id')->references('img_id')->on('images');
            $table->foreign('author_id')->references('id')->on('authors');
            $table->foreign('google_id')->references('google_id')->on('google_accounts');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
