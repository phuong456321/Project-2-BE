<?php

use App\Http\Controllers\User\LoginController;
use App\Http\Controllers\User\RegisterController;
use App\Http\Controllers\User\ResetPasswordController;
use App\Http\Controllers\Song\SongController;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('login', [LoginController::class, 'login']);
Route::post('register', [RegisterController::class, 'register']);

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    // Define your protected routes here
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('logout', [LoginController::class, 'logout']);
    });
});

// Email verification routes
Route::get('email/verify/{id}/{hash}', [RegisterController::class, 'verify'])
    ->name('verification.verify')
    ->middleware(['signed']); // Ensure the route is signed

Route::get('email/verification-notification', [RegisterController::class, 'sendVerificationEmail'])
    ->name('verification.send')
    ->middleware(['auth:sanctum']);

Route::post('/forgot-password', [ResetPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::post('/reset-password/{token}', [ResetPasswordController::class, 'reset'])->name('password.reset');

//Music
Route::post('upload-song', [SongController::class, 'uploadSong']);
Route::get('get-song/{id}', [SongController::class, 'getSong']);
