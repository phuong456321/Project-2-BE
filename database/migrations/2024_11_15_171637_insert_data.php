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
        $imageData = file_get_contents(public_path('images/default-avatar.webp'));
        $imageName = Str::uuid() . '.webp';
        $image = Storage::disk('public')->put('images/' . $imageName, $imageData);
        DB::table('images')->insert([
            'img_name' => $imageName,
            'img_path' => 'images/' . $imageName,
            'category' => 'avatar',
        ]);

        DB::table('genres')->insert([
            [
                'name' => 'Pop Music',
            ],
            [
                'name' => 'Rock Music',
            ],
            [
                'name' => 'Rap/Hip-hop Music',
            ],
            [
                'name' => 'Romantic Music',
            ],
            [
                'name' => 'Revolutionary Music',
            ],
            [
                'name' => 'Folk Music',
            ],
            [
                'name' => 'R&B Music',
            ],
            [
                'name' => 'Red Music', // Nhạc Đỏ
            ],
            [
                'name' => 'Bolero Music',
            ],
            [
                'name' => 'Children’s Music',
            ],
            [
                'name' => 'Dance/Electronic Music',
            ],
            [
                'name' => 'Acoustic Music',
            ],
            [
                'name' => 'Indie Music (Vietnam)',
            ],
            [
                'name' => 'Classical Music',
            ],
            [
                'name' => 'Buddhist Music',
            ],
            [
                'name' => 'Film Music',
            ],
            [
                'name' => 'Spring Music',
            ],
            [
                'name' => 'Chamber Music',
            ],
            [
                'name' => 'Vietnamese EDM',
            ],
            [
                'name' => 'Chill/Lo-fi Music',
            ],
            [
                'name' => 'Vietnamese Lo-fi',
            ],
            [
                'name' => 'Meditation Music',
            ],
            [
                'name' => 'Comedy Music',
            ],
            [
                'name' => 'Teen Music',
            ],
            [
                'name' => 'Orchestral Music',
            ],
            [
                'name' => 'Remix Music',
            ],
            [
                'name' => 'Homeland Music',
            ],
            [
                'name' => 'Musical Theatre',
            ],
        ]);
        
        
        DB::table('users')->insert([
            [
                'name' => 'admin',
                'email' => 'nulltifly@gmail.com',
                'password' => Hash::make('@Phuong456321'),
                'plan' => 'premium',
                'email_verified_at' => now(),
                'avatar_id' => 1,
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'admin',
                'email' => 'admin@nulltifly.com',
                'password' => Hash::make('nulladmin1111'),
                'plan' => 'premium',
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
                'description' => 'Promotional monthly plan. \n\nNo ads.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Premium 6 Months',
                'cycles' => 'six_months',
                'price' => 49.99,  // Giá cho gói 6 tháng
                'description' => 'Promotional 6-month plan. \n\nNo ads.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Premium Yearly',
                'cycles' => 'yearly',
                'price' => 89.99,  // Giá cho gói 1 năm
                'description' => 'Promotional yearly plan. \n\nNo ads.',
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
