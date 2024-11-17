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
        Schema::create('authors', function (Blueprint $table) {
            $table->id();
            $table->string("author_name");
            $table->string("bio");
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('img_id');
            $table->unsignedBigInteger("area_id");
            $table->timestamps();
            $table->foreign('img_id')->references('img_id')->on('images');
            $table->foreign('area_id')->references('id')->on('areas');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('authors');
    }
};
