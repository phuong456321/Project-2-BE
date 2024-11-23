<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\image;
use App\Models\User;
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
        $finduser = User::where('google_id', $user->id)->first();
        if ($finduser) {
            Auth::login($finduser);
        } else {
            $checkUser = User::where('email', $user->email)->first();
            if (!$checkUser || $checkUser->avatar_id == 1) {
                // Get the avatar URL from Google
                $avatarUrl = $user->avatar;

                // Convert the avatar image to Base64
                $imageData = file_get_contents($avatarUrl);
                $base64 = base64_encode($imageData);
                $image = image::create([
                    'img_name' => $user->name . ' avatar',
                    'img_path' => $base64,
                    'category' => 'avatar',
                ]);
            } else {
                $image = image::find($checkUser->avatar_id);
            }
            $newUser = User::updateOrCreate(
                ['email' => $user->email],
                [
                    'name' => $user->name,
                    'email_verified_at' => now(),
                    'google_id' => $user->id,
                    'password' => Hash::make('abcd1234'),
                    'avatar_id' => $image->id,
                    'verified' => true,
                ]
            );
            Auth::login($newUser);
        }
        return redirect()->intended('/');
    }
}
