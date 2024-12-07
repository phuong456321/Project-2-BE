<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $imageData = file_get_contents(public_path('images/default-avatar.jpg'));
        $imageName = Str::uuid() . '.webp';
        $image = Storage::disk('public')->put('images/' . $imageName, $imageData);
        DB::table('images')->insert([
            'img_name' => $imageName,
            'img_path' => 'images/' . $imageName,
            'category' => 'avatar',
        ]);

        DB::table('genres')->insert([
            [
                'name' => 'Nhạc Pop',
            ],
            [
                'name' => 'Nhạc Rock',
            ],
            [
                'name' => 'Nhạc Rap/Hip-hop',
            ],
            [
                'name' => 'Nhạc Trữ tình',
            ],
            [
                'name' => 'Nhạc Cách mạng',
            ],
            [
                'name' => 'Nhạc Dân ca',
            ],
            [
                'name' => 'Nhạc R&B',
            ],
            [
                'name' => 'Nhạc Đỏ',
            ],
            [
                'name' => 'Nhạc Bolero',
            ],
            [
                'name' => 'Nhạc Thiếu nhi',
            ],
            [
                'name' => 'Nhạc Dance/Electronic',
            ],
            [
                'name' => 'Nhạc Acoustic',
            ],
            [
                'name' => 'Nhạc Indie Việt',
            ],
            [
                'name' => 'Nhạc Cổ điển',
            ],
            [
                'name' => 'Nhạc Phật giáo',
            ],
            [
                'name' => 'Nhạc Phim',
            ],
            [
                'name' => 'Nhạc Xuân',
            ],
            [
                'name' => 'Nhạc Thính phòng',
            ],
            [
                'name' => 'Nhạc EDM Việt',
            ],
            [
                'name' => 'Nhạc Chill/Lo-fi',
            ],
            [
                'name' => 'Nhạc Lofi Việt',
            ],
            [
                'name' => 'Nhạc Thiền',
            ],
            [
                'name' => 'Nhạc Hài kịch',
            ],
            [
                'name' => 'Nhạc Trẻ',
            ],
            [
                'name' => 'Nhạc Hoà tấu',
            ],
            [
                'name' => 'Nhạc Remix',
            ],
            [
                'name' => 'Nhạc Quê hương',
            ],
            [
                'name' => 'Nhạc Kịch',
            ],
        ]);
        
        DB::table('users')->insert([
            [
                'name' => 'admin',
                'email' => 'admin@nulltifly.com',
                'password' => Hash::make('abcd1234'),
                'email_verified_at' => now(),
                'avatar_id' => 1,
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        DB::table('products')->insert([
            [
                'name' => 'Free',
                'cycles' => 'free',
                'price' => 0,
                'description' => 'Free plan with ads.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Premium Monthly',
                'cycles' => 'monthly',
                'price' => 9.99,  // Giá cho gói 1 tháng
                'description' => 'Gói dịch vụ Premium, thanh toán hàng tháng.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Premium 6 Months',
                'cycles' => 'six_months',
                'price' => 49.99,  // Giá cho gói 6 tháng
                'description' => 'Gói dịch vụ Premium, thanh toán mỗi 6 tháng.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Premium Yearly',
                'cycles' => 'yearly',
                'price' => 89.99,  // Giá cho gói 1 năm
                'description' => 'Gói dịch vụ Premium, thanh toán hàng năm với mức giá ưu đãi.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('images')->truncate();
        DB::table('users')->truncate();
        DB::table('products')->truncate();
        DB::table('genres')->truncate();
    }
};
