<?php

namespace Database\Seeders;

use App\Models\Song;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;

class SongSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $songs = [
            [
                'song_name' => 'Tràn bộ nhớ',
                'author_id' => 1,
                'area_id' => 2,
                'genre_id' => 1,
                'description' => 'Tràn bộ nhớ',
                'audio_path' => 'songs/1.mp3',
                'img_url' => 'https://i.scdn.co/image/ab67616d0000b2735f97cdad9ef958c67d8bbd62',
                'status' => 'published',
                'likes' => rand(0, 10000),
                'play_count' => rand(0, 100000),
                'lyric' => '',
            ],
            [
                'song_name' => 'Bình yên',
                'author_id' => 2,
                'area_id' => 2,
                'genre_id' => 1,
                'description' => 'Bình yên',
                'audio_path' => 'songs/2.mp3',
                'img_url' => 'https://i.scdn.co/image/ab67616d0000b273be066d7fd668d8a0672b1245',
                'status' => 'published',
                'likes' => rand(0, 10000),
                'play_count' => rand(0, 100000),
                'lyric' => '',
            ],
            [
                'song_name' => 'Exit sign',
                'author_id' => 3,
                'area_id' => 2,
                'genre_id' => 1,
                'description' => 'Exit sign',
                'audio_path' => 'songs/3.mp3',
                'img_url' => 'https://i.scdn.co/image/ab67616d0000b273c006b0181a3846c1c63e178f',
                'status' => 'published',
                'likes' => rand(0, 10000),
                'play_count' => rand(0, 100000),
                'lyric' => '',
            ],
            [
                'song_name' => 'Đừng làm trái tim anh đau',
                'author_id' => 4,
                'area_id' => 2,
                'genre_id' => 1,
                'description' => 'Đừng làm trái tim anh đau',
                'audio_path' => 'songs/4.mp3',
                'img_url' => 'https://i.scdn.co/image/ab67616d0000b273a1bc26cdd8eecd89da3adc39',
                'status' => 'published',
                'likes' => rand(0, 10000),
                'play_count' => rand(0, 100000),
                'lyric' => '',
            ],
            [
                'song_name' => 'Phóng Zìn Zìn',
                'author_id' => 5,
                'area_id' => 2,
                'genre_id' => 1,
                'description' => 'Phóng Zìn Zìn',
                'audio_path' => 'songs/5.mp3',
                'img_url' => 'https://i.scdn.co/image/ab67616d0000b273d7d3c1abbc451131fa7523e8',
                'status' => 'published',
                'likes' => rand(0, 10000),
                'play_count' => rand(0, 100000),
                'lyric' => '',
            ]
        ];
        foreach ($songs as $song) {
            $image = Image::create([
                'img_name' => $song['song_name'],
                'img_path' => file_get_contents($song['img_url']),
                'category' => 'song_img',
            ]);
            $audioContent = file_get_contents(public_path($song['audio_path'])); // Lấy nội dung tệp từ public
            $audioFileName = 'music/' . basename($song['audio_path']); // Đặt tên file
            Storage::disk('public')->put($audioFileName, $audioContent); // Lưu file

            Song::create([
                'song_name' => $song['song_name'],
                'author_id' => $song['author_id'],
                'area_id' => $song['area_id'],
                'genre_id' => $song['genre_id'],
                'description' => $song['description'],
                'audio_path' => $audioFileName,
                'img_id' => $image->id,
                'status' => $song['status'],
                'likes' => $song['likes'],
                'play_count' => $song['play_count'],
                'lyric' => $song['lyric'],
            ]);
        }
    }
}
