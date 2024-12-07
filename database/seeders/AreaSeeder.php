<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    public function run(): void
    {
        $continents = [
            [
                'name' => 'Asia',
                'countries' => [
                    'Vietnam' => 'Việt Nam',
                    'Korea' => 'Hàn Quốc',
                    'Japan' => 'Nhật Bản', 
                    'China' => 'Trung Quốc',
                    'Taiwan' => 'Đài Loan',
                    'Thailand' => 'Thái Lan',
                    'Indonesia' => 'Indonesia',
                    'Philippines' => 'Philippines',
                    'India' => 'Ấn Độ'
                ]
            ],
            [
                'name' => 'Europe',
                'countries' => [
                    'UK' => 'Anh',
                    'France' => 'Pháp',
                    'Germany' => 'Đức',
                    'Italy' => 'Ý',
                    'Spain' => 'Tây Ban Nha',
                    'Sweden' => 'Thụy Điển',
                    'Netherlands' => 'Hà Lan',
                    'Russia' => 'Nga'
                ]
            ],
            [
                'name' => 'America',
                'countries' => [
                    'USA' => 'Mỹ',
                    'Canada' => 'Canada',
                    'Brazil' => 'Brazil',
                    'Mexico' => 'Mexico',
                    'Argentina' => 'Argentina',
                    'Colombia' => 'Colombia',
                    'Puerto Rico' => 'Puerto Rico',
                    'Cuba' => 'Cuba'
                ]
            ],
            [
                'name' => 'Africa',
                'countries' => [
                    'Nigeria' => 'Nigeria',
                    'South Africa' => 'Nam Phi',
                    'Egypt' => 'Ai Cập',
                    'Morocco' => 'Ma-rốc',
                    'Ghana' => 'Ghana',
                    'Kenya' => 'Kenya'
                ]
            ],
            [
                'name' => 'Oceania',
                'countries' => [
                    'Australia' => 'Úc',
                    'New Zealand' => 'New Zealand'
                ]
            ]
        ];

        // Tạo các khu vực cha cho từng châu lục
        foreach ($continents as $continent) {
            $continentArea = Area::create(['name' => $continent['name']]);

            // Tạo các quốc gia (khu vực con)
            foreach ($continent['countries'] as $code => $name) {
                Area::create([
                    'name' => $name,
                    'parents_id' => $continentArea->id
                ]);
            }
        }
    }
}
