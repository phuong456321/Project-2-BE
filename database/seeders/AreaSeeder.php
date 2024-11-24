<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{

    public function run(): void
    {
        // Danh sách các khu vực theo châu lục
        $continents = [
            [
                'name' => 'Asia',
                'countries' => [
                    'Vietnam', 'Thailand', 'Japan', 'South Korea', 'China', 'India', 'Indonesia', 'Malaysia', 'Singapore'
                ]
            ],
            [
                'name' => 'Europe',
                'countries' => [
                    'France', 'Germany', 'UK', 'Italy', 'Spain', 'Sweden', 'Russia', 'Belgium', 'Netherlands'
                ]
            ],
            [
                'name' => 'America',
                'countries' => [
                    'USA', 'Canada', 'Brazil', 'Mexico', 'Argentina', 'Chile', 'Colombia', 'Peru'
                ]
            ],
            [
                'name' => 'Africa',
                'countries' => [
                    'Nigeria', 'South Africa', 'Kenya', 'Egypt', 'Ghana', 'Ethiopia', 'Morocco'
                ]
            ],
            [
                'name' => 'Oceania',
                'countries' => [
                    'Australia', 'New Zealand', 'Fiji', 'Papua New Guinea'
                ]
            ],
            [
                'name' => 'Antarctica',
                'countries' => [] // Châu Nam Cực không có quốc gia nào
            ],
        ];
        // Tạo các khu vực cha cho từng châu lục
        foreach ($continents as $continent) {
            $continentArea = Area::create(['name' => $continent['name']]);

            // Tạo các quốc gia (khu vực con)
            foreach ($continent['countries'] as $country) {
                Area::create([
                    'name' => $country,
                    'parents_id' => $continentArea->id
                ]);
            }
        }
    }
}
