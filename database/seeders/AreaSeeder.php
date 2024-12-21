<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            'Vietnam' => 'Vietnam',
            'Korea' => 'Korea',
            'Japan' => 'Japan',
            'China' => 'China',
            'Taiwan' => 'Taiwan',
            'Thailand' => 'Thailand',
            'Indonesia' => 'Indonesia',
            'Philippines' => 'Philippines',
            'India' => 'India',
            'UK' => 'UK',
            'France' => 'France',
            'Germany' => 'Germany',
            'Italy' => 'Italy',
            'Spain' => 'Spain',
            'Sweden' => 'Sweden',
            'Netherlands' => 'Netherlands',
            'Russia' => 'Russia',
            'USA' => 'USA',
            'Canada' => 'Canada',
            'Brazil' => 'Brazil',
            'Mexico' => 'Mexico',
            'Argentina' => 'Argentina',
            'Colombia' => 'Colombia',
            'Puerto Rico' => 'Puerto Rico',
            'Cuba' => 'Cuba',
            'Nigeria' => 'Nigeria',
            'South Africa' => 'South Africa',
            'Egypt' => 'Egypt',
            'Morocco' => 'Morocco',
            'Ghana' => 'Ghana',
            'Kenya' => 'Kenya',
            'Australia' => 'Australia',
            'New Zealand' => 'New Zealand'
        ];
        

        // Tạo các khu vực cha cho từng châu lục
        foreach ($countries as $continent) {
            $continentArea = Area::create(['name' => $continent['name']]);
        }
    }
}
