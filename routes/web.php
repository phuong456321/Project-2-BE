<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Middleware\EnsureEmailIsVerified;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\LoginController;
use App\Http\Controllers\User\LoginGoogleController;
use App\Http\Controllers\User\RegisterController;
use App\Http\Controllers\User\ResetPasswordController;
use App\Http\Controllers\Song\SongController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\Playlist\PlaylistController;
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
Route::get('/likesong', function () {
    return view('user/likesong');
})->name('likesong');
Route::get('/playist', function () {
    return view('user/playist');
})->name('playist');

//Route Login
Route::get('login', [LoginController::class, 'index'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login');

Route::post('register', [RegisterController::class, 'register'])->name('register');
Route::get('login-google', [LoginGoogleController::class, 'redirectToGoogle'])->name('login-google');
Route::get('login-google/callback', [LoginGoogleController::class, 'handleGoogleCallBack']);


Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    // Define your protected routes here
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    Route::middleware(['web'])->group(function () {
        Route::get('link-google', [LoginGoogleController::class, 'linkGoogleAccount'])->name('link-google');
        Route::get('link-google/callback', [LoginGoogleController::class, 'handleLinkGoogleCallback']);
    });
    Route::get('check', function (Request $request) {
        return response()->json(Auth::user(), 200);
    });
    Route::post('upload-song', [SongController::class, 'uploadSong']);

    //Playlist
    Route::get('get-playlist', [PlaylistController::class, 'getPlaylist'])->name('library');
    Route::get('get-song-in-playlist/{playlist_id}', [PlaylistController::class, 'getSongInPlaylist'])->name('playlist');

    Route::post('create-like-playlist', [PlaylistController::class, 'createLikePlaylist']);
    Route::post('create-playlist', [PlaylistController::class, 'createPlaylist']);
    Route::post('add-song-to-playlist', [PlaylistController::class, 'addSongToPlaylist']);
    Route::post('delete-song-in-playlist', [PlaylistController::class, 'removeSongFromPlaylist']);
    Route::post('delete-playlist', [PlaylistController::class, 'deletePlaylist'])->name('delete');


    //Profile
    Route::get('profile/{id}', [ProfileController::class, 'showProfile']);
    Route::post('profile/{id}', [ProfileController::class, 'updateProfile']);

});

// Email verification routes
// Trang yêu cầu xác thực email
Route::get('/email/verify', function () {
    return view('emails.verify-email');
})->middleware('auth:sanctum')->name('verification.notice');
Route::get('email/verify/{token}', [RegisterController::class, 'verify'])
    ->name('verification.verify');

// Gửi lại email xác thực
Route::post('/email/verification-notification', [RegisterController::class, 'resendVerificationEmail'])
    ->middleware(['auth:sanctum', 'throttle:6,1'])
    ->name('verification.send');

Route::post('/forgot-password', [ResetPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::post('/reset-password/{token}', [ResetPasswordController::class, 'reset'])->name('password.reset');


Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.request');
//Music
Route::get('get-song/{id}', [SongController::class, 'getSong']);

Route::get('getsong/{id}', function ($id) {
    $song = Song::find($id);
    return '<audio controls><source src="' . Storage::url($song->audio_path) . '" type="audio/mpeg">Your browser does not support the audio element.</audio>';
});

Route::get('/admin', [AdminController::class, 'dashboard']);
