<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{3,}$/',
                'password' => 'required|min:6',
                'password_confirmation' => 'required|same:password'
            ]);
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'avatar_id' => 1,
            ]);
            $this->sendVerificationEmail($user);
            return redirect()->route('home')->with('success', 'User registered successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function sendVerificationEmail(User $user)
    {
        // Check the time of the last sent email
        if ($user->email_verification_sent_at && $user->email_verification_sent_at->addMinutes(5)->isFuture()) {
            return redirect()->back()->with('error', 'Please wait before requesting to resend the email.');
        }

        // Create a unique token
        $token = Str::random(64);
        $hashedToken = hash('sha256', $token);
        $user->update([
            'email_verification_token' => $hashedToken,
            'email_verification_sent_at' => now(),
        ]);

        // Send email with verification link containing the token
        try {
            Mail::to($user->email)->send(new VerifyEmail($token));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $user->email);
        }

        return redirect()->back()->with('success', 'Verification email sent');
    }
    public function resendVerificationEmail(Request $request)
    {
        try {
            $user = $request->user(); // Get the current user from session or authentication

            // Check if the email has been verified
            if ($user->hasVerifiedEmail()) {
                return redirect()->route('home')->with('info', 'Your email has been verified.');
            }

            // Check the time of the last sent email
            if ($user->email_verification_sent_at && $user->email_verification_sent_at->addMinutes(5)->isFuture()) {
                return redirect()->back()->with('error', 'Please wait before requesting to resend the email.');
            }

            // Resend the verification email
            $this->sendVerificationEmail($user);

            return redirect()->back()->with('success', 'Verification email has been resent.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function verify(Request $request, $token)
    {
        try {
            // Find user by token
            $hashedToken = hash('sha256', $token);
            $user = User::where('email_verification_token', $hashedToken)->first();

            // Check if the email has been verified
            if ($user->hasVerifiedEmail()) {
                return redirect()->route('home')->with('info', 'Email has been verified.');
            }

            // Check if the token has expired
            if ($user->email_verification_sent_at->addMinutes(60)->isPast()) {
                return redirect()->route('home')->with('error', 'The verification link has expired. Please request to resend.');
            }

            // Mark the email as verified
            $user->markEmailAsVerified();

            // Remove the token to prevent reuse
            $user->update(['email_verification_token' => null]);

            return redirect()->route('home')->with('success', 'Email verification successful.');
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', $e->getMessage());
        }
    }
}