<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginGoogleController extends Controller
{
    public function redirectToGoogle(){
        return Socialite::driver('google')->redirect();
    }
    public function handleGoogleCallBack(){
        $user = Socialite::driver('google')->user();
        $finduser = User::where('google_id', $user->id)->first();
        if($finduser){
            Auth::login($finduser);
        }else{
            $newUser = User::updateOrCreate(
                ['email' => $user->email],[
                'name' => $user->name,
                'google_id' => $user->id,
                'password' => encrypt('abcd1234'),
            ]);
            Auth::login($newUser);
        }
        return redirect()->intended('home');
    }

    public function linkGoogleAccount(){
        return Socialite::driver('google')->redirect();
    }

    public function handleLinkGoogleCallback()
    {
        $user = Socialite::driver('google')->user();
        $currentUser = auth()->user();

        // Check if the Google ID is already linked
        if ($currentUser->google_id) {
            return response()->json(['message' => 'Google account is already linked.'], 400);
        }

        // Link the Google account to the current user
        $currentUser->google_id = $user->id;
        $currentUser->save();

        // Log the user in
        Auth::login($currentUser);

        return response()->json(['message' => 'Google account linked successfully.'], 200);
    }
}
