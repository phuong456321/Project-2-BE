<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class LoginController extends Controller
{
    public function index()
    {
        flash()->option('timeout', 1000)->warning('Please login to continue');
        return redirect()->route('home');
    }
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => ['required', 'email'],
                'password' => ['required']
            ]);
            if (!$validator->passes()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Thử đăng nhập
            if (!Auth::attempt($request->only('email', 'password'))) {
                flash()->option('timeout', 2000)->error('Email or Password is incorrect');
                return redirect()->back()->with('message', 'Email or Password is incorrect')->withInput();
            }
            $user = User::where('email', $request->email)->first();
            Auth::login($user);
            // Check if the user is verified
            if (!$user->hasVerifiedEmail()) {
                return redirect()->with('error', 'Your email address is not verified. Please check your email for the verification link.');
            }
            flash()->option('timeout', 2000)->success('Logged in successfully');
            return redirect()->route('home');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->session()->forget('session_id');
            $request->session()->flush();
            Auth::guard('web')->logout();
            flash()->option('timeout', 2000)->success('Logged out successfully');
            return redirect()->route('home');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
