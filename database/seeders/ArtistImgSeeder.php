<?php

namespace Database\Seeders;

use App\Models\Image;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArtistImgSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $artists = [
            [
                'name' => 'ANH TRAI "SAY HI"',
                'image_url' => 'https://i.scdn.co/image/ab6761610000517497d758a5602772c33428697e',
            ],
            [
                'name' => 'Vũ.',
                'image_url' => 'https://i.scdn.co/image/ab676161000051742d7150aa7e90e9a85610ab3d',
            ],
            [
                'name' => 'HIEUTHUHAI',
                'image_url' => 'https://i.scdn.co/image/ab67616100005174e1cbc9e7ba8fbc5d7738ea51',
            ],
            [
                'name' => 'Sơn Tùng M-TP',
                'image_url' => 'https://i.scdn.co/image/ab676161000051745a79a6ca8c60e4ec1440be53',
            ],
            [
                'name' => 'tlinh',
                'image_url' => 'https://i.scdn.co/image/ab67616100005174230e62752ca87da1d85d0445',
            ],
            [
                'name' => 'Da LAB',
                'image_url' => 'https://i.scdn.co/image/ab6761610000517462c092ca08054a8ce883ef7e',
            ],
            [
                'name' => 'AMEE',
                'image_url' => 'https://i.scdn.co/image/ab67616100005174fd70279a04a9b3796e7dcd4d',
            ],
            [
                'name' => 'W/N',
                'image_url' => 'https://i.scdn.co/image/ab67616100005174316c0f0bc6cf3a29c203ab1e',
            ],
            [
                'name' => 'Emcee L (Da LAB)',
                'image_url' => 'https://i.scdn.co/image/ab6761610000517492dc4a2cbffbddb59f825fe0',
            ],
            [
                'name' => 'JustaTee',
                'image_url' => 'https://i.scdn.co/image/ab67616100005174de3d3210433dd11c07678420',
            ],
            [
                'name' => 'Wren Evans',
                'image_url' => 'https://i.scdn.co/image/ab6761610000517410e658dffbc09c792ad3969c',
            ],
            [
                'name' => 'RAP VIỆT',
                'image_url' => 'https://i.scdn.co/image/ab676161000051749d2bdcb58736ac5fbd385d11',
            ],
            [
                'name' => 'SOOBIN',
                'image_url' => 'https://i.scdn.co/image/ab67616100005174b9c9e23c646125922719489e',
            ],
            [
                'name' => 'Vũ Cát Tường',
                'image_url' => 'https://i.scdn.co/image/ab67616100005174e0e7dda97f41aae140ac029d',
            ],
            [
                'name' => 'GREY D',
                'image_url' => 'https://i.scdn.co/image/ab67616100005174d23b0ac7f33678be5753e1f5',
            ],
            [
                'name' => 'RPT MCK',
                'image_url' => 'https://i.scdn.co/image/ab67616100005174b97791c136d7354ad7792555',
            ],
            [
                'name' => 'Low G',
                'image_url' => 'https://i.scdn.co/image/ab67616100005174655c4a2a98366c2a276f6e9b',
            ],
            [
                'name' => 'Madihu',
                'image_url' => 'https://i.scdn.co/image/ab676161000051743bcc23d31e8962897b7d3e2c',
            ],
            [
                'name' => 'Đen',
                'image_url' => 'https://i.scdn.co/image/ab6761610000517491d2d39877c13427a2651af5',
            ],
            [
                'name' => 'WEAN',
                'image_url' => 'https://i.scdn.co/image/ab6761610000517424b6a2cff3114e58882c5aaf',
            ],
        ];

        foreach ($artists as $artist) {
            $imageData = file_get_contents($artist['image_url']);
            $imageName = Str::uuid() . '.webp';
            $image = Storage::disk('public')->put('images/' . $imageName, $imageData);
            Image::create([
                'img_name' => $imageName,
                'img_path' => 'images/' . $imageName,
                'category' => 'artist_img',
            ]);
        }
    }
}
