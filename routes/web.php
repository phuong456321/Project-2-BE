<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Song\AudioController;
use App\Http\Middleware\EnsureEmailIsVerified;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\LoginController;
use App\Http\Controllers\User\LoginGoogleController;
use App\Http\Controllers\User\RegisterController;
use App\Http\Controllers\User\ResetPasswordController;
use App\Http\Controllers\Song\SongController;
use App\Http\Controllers\User\ProfileController;
use App\Models\Author;
use App\Models\Song;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    $authors = Author::inRandomOrder()->take(9)->get();
    return view('user/home', compact('authors')); // Hoặc trả về view trang chủ của bạn
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


//Route Login
Route::get('login', [LoginController::class, 'index'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login');

Route::post('register', [RegisterController::class, 'register'])->name('register');
Route::get('login-google', [LoginGoogleController::class, 'redirectToGoogle'])->name('login-google');
Route::get('login-google/callback', [LoginGoogleController::class, 'handleGoogleCallBack']);


Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    // Define your protected routes here
    Route::middleware(['web'])->group(function () {
        Route::get('link-google', [LoginGoogleController::class, 'linkGoogleAccount'])->name('link-google');
        Route::get('link-google/callback', [LoginGoogleController::class, 'handleLinkGoogleCallback']);
    });
    Route::get('check', function (Request $request) {
        return response()->json(Auth::user(), 200);
    });
    Route::post('upload-song', [SongController::class, 'uploadSong']);

    Route::get('profile/{id}', [ProfileController::class, 'showProfile']);
    Route::post('profile/{id}', [ProfileController::class, 'updateProfile']);

    Route::get('/library', function () {
        return view('user/library');
    });
    Route::get('/likesong', function () {
        return view('user/likesong');
    })->name('likesong');
    Route::get('/playist', function () {
        return view('user/playist');
    })->name('playist');
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    // Trang yêu cầu xác thực email
    Route::get('/email/verify', function () {
        return view('emails.verify-email');
    })->middleware('auth:sanctum')->name('verification.notice');
    // Xác thực email
    Route::get('email/verify/{token}', [RegisterController::class, 'verify'])
        ->name('verification.verify');

    // Gửi lại email xác thực
    Route::post('/email/verification-notification', [RegisterController::class, 'resendVerificationEmail'])
        ->middleware(['auth:sanctum', 'throttle:6,1'])
        ->name('verification.send');
});

Route::post('/forgot-password', [ResetPasswordController::class, 'sendResetLinkEmail'])->name('password.email');



Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.request');
//Music
Route::get('get-song/{id}', [SongController::class, 'getSong']);

Route::get('/search', [SongController::class, 'searchSong'])->name('searchsong');

Route::get('/admin', [AdminController::class, 'dashboard']);

Route::get('/audio/music/{filePath}', [AudioController::class, 'streamAudio']);


// Route gửi liên kết đặt lại mật khẩu đến email của người dùng
Route::post('email', [ResetPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// Route hiển thị form đặt lại mật khẩu (nếu bạn sử dụng giao diện)
Route::get('reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset.form');

// Route thực hiện đặt lại mật khẩu
Route::post('reset/{token}', [ResetPasswordController::class, 'reset'])->name('password.reset');

Route::get('image/{id}', [ProfileController::class, 'showImage']);