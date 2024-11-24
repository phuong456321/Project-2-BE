<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\User;
use App\Models\GoogleAccount;  // Model GoogleAccount mới tạo
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class LoginGoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallBack()
    {
        $user = Socialite::driver('google')->user();
        
        // Kiểm tra nếu người dùng đã đăng nhập
        $currentUser = Auth::user();

        if ($currentUser) {
            // Nếu người dùng đã đăng nhập nhưng email không trùng với email từ Google
            if ($currentUser->email !== $user->email) {
                // Kiểm tra xem đã có liên kết Google chưa
                $googleAccount = GoogleAccount::where('google_id', $user->id)->first();

                if (!$googleAccount) {
                    // Nếu chưa có GoogleAccount liên kết, tạo mới
                    GoogleAccount::create([
                        'google_id' => $user->id,
                        'email' => $user->email, // Sử dụng email của người dùng hiện tại
                        'name' => $user->name,
                        'avatar' => $user->avatar,
                    ]);
                    
                    // Cập nhật thông tin Google vào bảng User (nếu cần thiết)
                    $currentUser->google_id = $user->id;
                    $currentUser->save();
                }
            }

            // Kiểm tra nếu avatar_id của người dùng hiện tại là 1 (avatar mặc định)
            if ($currentUser->avatar_id == 1) {
                // Tạo hoặc tìm ảnh đại diện mới từ Google
                $imageData = file_get_contents($user->avatar);
                $image = Image::create([
                    'img_name' => $user->name . ' avatar',
                    'img_path' => $imageData,
                    'category' => 'avatar',
                ]);
                
                // Cập nhật avatar_id cho người dùng
                $currentUser->avatar_id = $image->id;
                $currentUser->save();
            }

            // Đăng nhập người dùng
            Auth::login($currentUser);
        } else {
            // Kiểm tra nếu người dùng chưa có tài khoản thì tạo mới
            $findUser = User::where('google_id', $user->id)->first();

            if ($findUser) {
                Auth::login($findUser);
            } else {
                $checkUser = User::where('email', $user->email)->first();

                if (!$checkUser) {
                    $imageData = file_get_contents($user->avatar);
                    $newImage = Image::create([
                        'img_name' => $user->name . ' avatar',
                        'img_path' => $imageData,
                        'category' => 'avatar',
                    ]);
                
                    $googleAccount = GoogleAccount::create([
                        'google_id' => $user->id,
                        'email' => $user->email,
                        'name' => $user->name,
                        'avatar_id' => $newImage->id,
                    ]);
                    
                    $newUser = User::create([
                        'email' => $user->email,
                        'name' => $user->name,
                        'password' => Hash::make('abcd1234'), // Đặt mật khẩu mặc định
                        'google_id' => $user->id,
                        'avatar_id' => $googleAccount->avatar_id, // Gán ảnh đại diện từ Google Account
                        'verified' => true,
                    ]);
                    $newUser->markEmailAsVerified();
                    Auth::login($newUser);
                } else {
                    if (is_null($checkUser->google_id)) {
                        $checkUser->google_id = $user->id;
                        $checkUser->save();
                    }
                    Auth::login($checkUser);
                }
            }
        }

        return redirect()->intended('/');
    }
}
