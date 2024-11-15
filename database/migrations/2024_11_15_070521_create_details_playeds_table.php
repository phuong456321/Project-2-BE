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
        Schema::create('details_played', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('recently_id');
            $table->unsignedBigInteger('song_id');
            $table->timestamps();
            $table->foreign('recently_id')->references('id')->on('recently_playeds');
            $table->foreign('song_id')->references('id')->on('songs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('details_playeds');
    }
};
