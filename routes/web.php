<?php

use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\LoginController;
use App\Http\Controllers\User\LoginGoogleController;
use App\Http\Controllers\User\RegisterController;
use App\Http\Controllers\User\ResetPasswordController;
use App\Http\Controllers\Song\SongController;
use App\Http\Controllers\User\ProfileController;
use App\Models\Song;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('user/home'); // Hoặc trả về view trang chủ của bạn
})->name('home');

Route::get('/premium', function () {
    return view('user/premium'); // Nếu file view là home.blade.php
});

Route::get('/albums', function () {
    return view('user/albums'); // Trang album
});
Route::get('/profile', function () {
    return view('user/profile'); // Trang profile
});
Route::get('/library', function () {
    return view('user/library'); 
});

Route::get('/discover', function () {
    return view('user/discover');
})->name('discover');




Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('login', [LoginController::class, 'login'])->name('login');
Route::post('register', [RegisterController::class, 'register'])->name('register');
Route::get('login-google', [LoginGoogleController::class, 'redirectToGoogle'])->name('login-google');
Route::get('login-google/callback', [LoginGoogleController::class, 'handleGoogleCallBack']);


Route::middleware(['auth:sanctum'])->group(function () {
    // Define your protected routes here
    Route::post('logout', [LoginController::class, 'logout']);
    Route::middleware(['web'])->group(function () {
        Route::get('link-google', [LoginGoogleController::class, 'linkGoogleAccount']);
        Route::get('link-google/callback', [LoginGoogleController::class, 'handleLinkGoogleCallback']);
    });
    Route::get('check', function (Request $request) {
        return response()->json(Auth::user(), 200);
    });
    Route::post('upload-song', [SongController::class, 'uploadSong']);

    Route::get('profile/{id}', [ProfileController::class, 'showProfile']);
    Route::post('profile', [ProfileController::class, 'updateProfile']);
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


Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.request');
//Music
Route::get('get-song/{id}', [SongController::class, 'getSong']);

Route::get('getsong/{id}', function ($id) {
    $song = Song::find($id);
    return '<audio controls><source src="' . Storage::url($song->audio_path) . '" type="audio/mpeg">Your browser does not support the audio element.</audio>';
});