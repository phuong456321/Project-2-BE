<?php

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

        DB::table('areas')->insert([
            'name' => 'VietNam',
            'parents_id' => null,
        ]);

        DB::table('genres')->insert([
            'name' => 'Rap',
            'parents_id' => null,
        ]);

        DB::table('authors')->insert([
            'author_name' => 'John Doe',
            'bio' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed ut purus eget sapien.',
            'img_id' => 1,
            'area_id' => 1,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('images')->truncate();
        DB::table('areas')->truncate();
        DB::table('genres')->truncate();
        DB::table('authors')->truncate();
    }
};
