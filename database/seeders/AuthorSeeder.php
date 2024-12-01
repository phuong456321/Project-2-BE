<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('authors')->insert([
            ['author_name' => 'ANH TRAI "SAY HI"', 'bio' => 'Nghệ sĩ nổi bật trong dòng nhạc Rap và Hip-hop Việt Nam', 'img_id' => 2, 'area_id' => 2],
            ['author_name' => 'Vũ.', 'bio' => 'Ca sĩ, nhạc sĩ nổi bật với những ca khúc Ballad nhẹ nhàng', 'img_id' => 3, 'area_id' => 2],
            ['author_name' => 'HIEUTHUHAI', 'bio' => 'Rapper, nổi bật với dòng nhạc trap và rap Việt', 'img_id' => 4, 'area_id' => 2],
            ['author_name' => 'Sơn Tùng M-TP', 'bio' => 'Nghệ sĩ nổi tiếng với các bản hit Pop, R&B', 'img_id' => 5, 'area_id' => 2],
            ['author_name' => 'tlinh', 'bio' => 'Nghệ sĩ rap, nổi bật trong dòng nhạc trap Việt', 'img_id' => 6, 'area_id' => 2],
            ['author_name' => 'Da LAB', 'bio' => 'Nhóm nhạc Rap Việt, nổi tiếng với các ca khúc như "Kẻ Lạ"', 'img_id' => 7, 'area_id' => 2],
            ['author_name' => 'AMEE', 'bio' => 'Ca sĩ trẻ nổi bật với âm nhạc Pop', 'img_id' => 8, 'area_id' => 2],
            ['author_name' => 'W/N', 'bio' => 'Nhóm nhạc Rap nổi bật tại Việt Nam', 'img_id' => 9, 'area_id' => 2],
            ['author_name' => 'Emcee L (Da LAB)', 'bio' => 'Thành viên của Da LAB, nổi bật trong dòng nhạc Rap', 'img_id' => 10, 'area_id' => 2],
            ['author_name' => 'JustaTee', 'bio' => 'Rapper, nổi tiếng với những ca khúc kết hợp giữa Rap và Pop', 'img_id' => 11, 'area_id' => 2],
            ['author_name' => 'Wren Evans', 'bio' => 'Nghệ sĩ âm nhạc Pop và R&B', 'img_id' => 12, 'area_id' => 21],
            ['author_name' => 'RAP VIỆT', 'bio' => 'Chương trình Rap nổi bật tại Việt Nam', 'img_id' => 13, 'area_id' => 2],
            ['author_name' => 'SOOBIN', 'bio' => 'Ca sĩ nổi bật với âm nhạc R&B và Pop', 'img_id' => 14, 'area_id' => 2],
            ['author_name' => 'Vũ Cát Tường', 'bio' => 'Ca sĩ và nhạc sĩ nổi tiếng với âm nhạc Pop và Ballad', 'img_id' => 15, 'area_id' => 2],
            ['author_name' => 'GREY D', 'bio' => 'Nghệ sĩ âm nhạc Hip-hop', 'img_id' => 16, 'area_id' => 2],
            ['author_name' => 'RPT MCK', 'bio' => 'Nghệ sĩ rap nổi bật với dòng nhạc trap', 'img_id' => 17, 'area_id' => 2],
            ['author_name' => 'Low G', 'bio' => 'Nghệ sĩ rap nổi bật trong cộng đồng hip-hop Việt', 'img_id' => 18, 'area_id' => 2],
            ['author_name' => 'Madihu', 'bio' => 'Rapper nổi bật trong giới trẻ', 'img_id' => 19, 'area_id' => 2],
            ['author_name' => 'Đen', 'bio' => 'Rapper nổi tiếng với những bản rap mang tính xã hội sâu sắc', 'img_id' => 20, 'area_id' => 2],
            ['author_name' => 'WEAN', 'bio' => 'Nghệ sĩ trẻ trong làng nhạc R&B và Hip-hop Việt', 'img_id' => 21, 'area_id' => 2],
        ]);
    }
}
