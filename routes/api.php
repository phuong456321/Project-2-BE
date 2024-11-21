<?php

use App\Http\Controllers\User\LoginController;
use App\Http\Controllers\User\LoginGoogleController;
use App\Http\Controllers\User\RegisterController;
use App\Http\Controllers\User\ResetPasswordController;
use App\Http\Controllers\Song\SongController;
use App\Http\Controllers\Playlist\PlaylistController;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('login', [LoginController::class, 'login']);
Route::post('register', [RegisterController::class, 'register']);
Route::middleware(['web'])->group(function () {
    Route::get('login-google', [LoginGoogleController::class, 'redirectToGoogle']);
    Route::get('login-google/callback', [LoginGoogleController::class, 'handleGoogleCallBack']);
});

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
    
    //Playlist
    Route::post('create-playlist', [PlaylistController::class, 'createPlaylist']);
    Route::get('get-playlist/{user_id}', [PlaylistController::class, 'getPlaylist']);
    Route::post('create-like-playlist', [PlaylistController::class, 'createLikePlaylist']);
    Route::post('add-song-to-playlist', [PlaylistController::class, 'addSongToPlaylist']);
    Route::get('get-song-in-playlist/{playlist_id}', [PlaylistController::class, 'getSongInPlaylist']);
    Route::post('delete-song-in-playlist', [PlaylistController::class, 'removeSongFromPlaylist']);
    Route::post('delete-playlist/{playlist_id}', [PlaylistController::class, 'deletePlaylist']);


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


//Song
Route::post('upload-song', [SongController::class, 'uploadSong']);
Route::get('get-song/{id}', [SongController::class, 'getSong']);

    //index test
    Route::get('/music', function () {
        return view('Music'); 
    });
    Route::get('/stream-audio/{id}', [SongController::class, 'streamAudio']);
