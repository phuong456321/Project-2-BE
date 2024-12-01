<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminSongsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AuthorController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\Song\AudioController;
use App\Http\Middleware\EnsureEmailIsVerified;
use App\Models\Playlist;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\LoginController;
use App\Http\Controllers\User\LoginGoogleController;
use App\Http\Controllers\User\RegisterController;
use App\Http\Controllers\User\ResetPasswordController;
use App\Http\Controllers\Song\SongController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\Playlist\PlaylistController;
use App\Http\Controllers\User\PaymentController;
use App\Http\Controllers\User\PaymentWithStripeController;
use App\Models\Song;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Author;

Route::get('/', [AudioController::class, 'index'])->name('home');

Route::get('/checkout', [PaymentController::class, 'show'])->name('checkout.show');
Route::post('/checkout/process', [PaymentController::class, 'processPayment'])->name('checkout.process');

Route::get('/albums', function () {
    return view('user/albums'); // Trang album
});
Route::get('/profile', function () {
    return view('user/profile'); // Trang profile
});

//Route Login
Route::get('login', [LoginController::class, 'index'])->name('show.login');
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

    //Playlist
    Route::get('get-playlist', [PlaylistController::class, 'getPlaylist'])->name('library');
    Route::get('get-song-in-playlist/{playlist_id}', [PlaylistController::class, 'getSongInPlaylist'])->name('playlist');

    Route::post('create-like-playlist', [PlaylistController::class, 'createLikePlaylist']);
    Route::post('create-playlist', [PlaylistController::class, 'createPlaylist']);
    Route::post('add-song-to-playlist', [PlaylistController::class, 'addSongToPlaylist']);
    Route::post('delete-song-in-playlist', [PlaylistController::class, 'removeSongFromPlaylist']);
    Route::post('delete-playlist', [PlaylistController::class, 'deletePlaylist'])->name('delete');


    //Profile
    Route::get('profile/{id}', [ProfileController::class, 'showProfile'])->name('profile');
    Route::post('profile/{id}', [ProfileController::class, 'updateProfile']);

    Route::get('/library', function () {
        return view('user/library');
    });
    Route::get('/likesong', function () {
        return view('user/likesong');
    })->name('likesong');
    //Premium
    Route::get('/premium',[PaymentController::class, 'index'])->name('premium');
});
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    // Trang yêu cầu xác thực email
   
});

Route::post('/forgot-password', [ResetPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
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


Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.request');
//Music
Route::get('get-song/{id}', [SongController::class, 'getSong']);

Route::get('/search', [SongController::class, 'searchSong'])->name('searchsong');

Route::get('/audio/music/{filePath}', [AudioController::class, 'streamAudio']);


// Route gửi liên kết đặt lại mật khẩu đến email của người dùng
Route::post('email', [ResetPasswordController::class, 'sendResetLinkEmail'])->name('reset.password.email');

// Route hiển thị form đặt lại mật khẩu (nếu bạn sử dụng giao diện)
Route::get('reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset.form');

// Route thực hiện đặt lại mật khẩu
Route::post('reset/{token}', [ResetPasswordController::class, 'reset'])->name('password.reset');

Route::delete('/admin/user/{id}', [AdminController::class, 'deleteUser'])->name('admin.deleteUser');
Route::delete('/admin/song/{id}', [AdminController::class, 'deleteSong'])->name('admin.deleteSong');
Route::patch('/admin/song/{id}/status', [AdminController::class, 'updateStatus'])->name('admin.updateStatus');
Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [AdminController::class, 'manageUsers'])->name('manageUsers');
    Route::get('/authors', [AdminController::class, 'manageAuthors'])->name('manageAuthors');
    Route::get('/get-authors', [AuthorController::class, 'index'])->name('authors');
    Route::post('/create-author', [AuthorController::class, 'createAuthor']);
    Route::get('/get-users', [UserController::class, 'index'])->name('users');
    Route::post('/users/{user}/ban', [UserController::class, 'banUser'])->name('users.ban');
    Route::post('/users/{user}/unban', [UserController::class, 'unbanUser'])->name('users.unban');

    // Hiển thị danh sách bài hát với bộ lọc
    Route::get('/songs', [AdminSongsController::class, 'index'])->name('songs');

    // Cập nhật trạng thái bài hát
    Route::patch('/songs/{id}/status', [AdminSongsController::class, 'updateStatus'])->name('updateStatus');

    // Hiển thị popup chỉnh sửa bài hát
    Route::get('/songs/{id}/edit', [AdminSongsController::class, 'edit'])->name('editSong');

    // Xử lý lưu bài hát sau chỉnh sửa
    Route::put('/songs/{id}', [AdminSongsController::class, 'update'])->name('updateSong');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});


Route::prefix('stripe')->name('stripe.')->group(function () {
    Route::post('stripe', [PaymentWithStripeController::class, 'stripe'])->name('stripe');
    Route::get('success', [PaymentWithStripeController::class, 'success'])->name('success');
    Route::get('cancel', [PaymentWithStripeController::class, 'cancel'])->name('cancel');
});

Route::post('upload-image', [ImageController::class, 'storeImage'])->name('upload-image');

Route::get('image/{id}', [ImageController::class, 'showImage']);