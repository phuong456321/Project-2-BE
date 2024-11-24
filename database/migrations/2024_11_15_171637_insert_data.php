<?php

use Illuminate\Auth\Events\Verified;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('images')->insert([
            'img_name' => 'avatar_image.png',
            'img_path' => file_get_contents('public/img/avatar.jpg'),
            'category' => 'avatar',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('images')->truncate();
    }
};
