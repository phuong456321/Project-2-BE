<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AreaSeeder::class,
            ArtistImgSeeder::class,
            AuthorSeeder::class,
            SongSeeder::class,
        ]);
    }
}
