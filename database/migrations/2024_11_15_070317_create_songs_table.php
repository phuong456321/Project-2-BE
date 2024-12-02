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
        Schema::create('songs', function (Blueprint $table) {
            $table->id();
            $table->string('song_name');
            $table->unsignedBigInteger('author_id');
            $table->unsignedBigInteger('area_id');
            $table->unsignedBigInteger('genre_id');
            $table->string('description');
            $table->string('audio_path');
            $table->string('duration')->nullable();
            $table->unsignedBigInteger('img_id');
            $table->enum('status', ['published', 'deleted', 'inactive', 'pending']);
            $table->integer('likes')->default(0);
            $table->integer('play_count')->default(0);
            $table->timestamps();
            $table->text('lyric');
            $table->foreign('author_id')->references('id')->on('authors');
            $table->foreign('area_id')->references('id')->on('areas');
            $table->foreign('genre_id')->references('id')->on('genres');
            $table->foreign('img_id')->references('img_id')->on('images');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('songs');
    }
};
