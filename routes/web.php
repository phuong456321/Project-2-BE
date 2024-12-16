<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminSongsController;
use App\Http\Controllers\Admin\SongApprovalController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AuthorController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\Song\AudioController;
use App\Http\Controllers\Song\RecommendSongs;
use App\Http\Controllers\User\SearchHistoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\LoginController;
use App\Http\Controllers\User\LoginGoogleController;
use App\Http\Controllers\User\RegisterController;
use App\Http\Controllers\User\ResetPasswordController;
use App\Http\Controllers\Song\SongController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\Playlist\PlaylistController;
use App\Http\Controllers\User\PaymentController;
use App\Http\Controllers\User\PaymentWithMomoController;
use App\Http\Controllers\User\PaymentWithStripeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Song\PlayHistoryController;



Route::get('/', [RecommendSongs::class, 'index'])->name('home')->middleware('web');

Route::get('/checkout', [PaymentController::class, 'show'])->name('checkout.show');

Route::get('/loginservice', function () {
    return view('user/loginservice'); 
});
Route::get('/musicservice', function () {
    return view('user/musicservice'); 
});
Route::get('/albums', function () {
    return view('user/albums'); // Trang album
});
Route::get('/Profileuser', function () {
    return view('user/Profileuser'); // Trang profile
});

//Route Login
Route::get('login', [LoginController::class, 'index'])->name('show.login');
Route::post('login', [LoginController::class, 'login'])->name('login');

Route::post('register', [RegisterController::class, 'register'])->name('register');
Route::get('login-google', [LoginGoogleController::class, 'redirectToGoogle'])->name('login-google');
Route::get('login-google/callback', [LoginGoogleController::class, 'handleGoogleCallBack']);
Route::post('/save-search-history', [SearchHistoryController::class, 'store'])->name('save-search-history');

Route::middleware(['web', 'auth:sanctum', 'verified'])->group(function () {
    // Define your protected routes here
    Route::middleware(['web'])->group(function () {
        Route::get('link-google', [LoginGoogleController::class, 'redirectToGoogle'])->name('link-google');
        Route::get('link-google/callback', [LoginGoogleController::class, 'handleGoogleCallBack']);
    });
    Route::get('check', function (Request $request) {
        return response()->json(Auth::user(), 200);
    });
    Route::post('upload-song', [SongController::class, 'uploadSong'])->name('uploadSong');

    //Playlist
    Route::get('get-playlist', [PlaylistController::class, 'getPlaylist'])->name('library');
    Route::post('add-song-to-playlist', [PlaylistController::class, 'addSongToPlaylist']);
    Route::get('get-song-in-playlist/{playlist_id}', [PlaylistController::class, 'getSongInPlaylist'])->name('playlist');

    Route::post('create-like-playlist', [PlaylistController::class, 'createLikePlaylist']);
    Route::post('create-playlist', [PlaylistController::class, 'createPlaylist']);
    Route::post('delete-song-in-playlist', [PlaylistController::class, 'removeSongFromPlaylist']);
    Route::post('delete-playlist', [PlaylistController::class, 'deletePlaylist'])->name('delete');
    Route::get('get-playlist-songs/{playlist_id}', [PlaylistController::class, 'getPlaylistSongs'])->name('get-playlist-songs');

    //Song (like,....)
    Route::post('like-song', [PlaylistController::class, 'likeSong']);
    Route::post('check-like', [PlaylistController::class, 'checkIfLiked']);


    //Profile
    Route::get('profile/{id}', [ProfileController::class, 'showProfile'])->name('profile');
    Route::get('editprofile', [ProfileController::class, 'editProfile'])->name('editprofile');
    Route::post('editprofile', [ProfileController::class, 'updateProfile'])->name('updateprofile');

    Route::get('/library', function () {
        return view('user/library');
    });
    Route::get('/likesong', function () {
        return view('user/likesong');
    })->name('likesong');
    //Premium
    Route::get('/premium', [PaymentController::class, 'index'])->name('premium');
    Route::get('/upload-song', [SongController::class, 'index'])->name('upload');

    Route::get('/recommend-songs', [RecommendSongs::class, 'index'])->name('recommend-songs');

    Route::post('/notifications/{id}/mark-as-read', [ProfileController::class, 'markAsRead']);
    Route::post('/notifications/mark-all-as-read', [ProfileController::class, 'markAllAsRead']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
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

Route::get('uploadedsong', [ProfileController::class, 'uploadedsong'])->name('uploadedsong');
//Music
Route::get('get-song/{id}', [SongController::class, 'getSong']);

Route::get('/search', [SongController::class, 'searchSong'])->name('searchsong');

Route::get('/audio/music/{filePath}', [AudioController::class, 'streamAudio']);


// Route gửi liên kết đặt lại mật khẩu đến email của người dùng
Route::post('email', [ResetPasswordController::class, 'sendResetLinkEmail'])->name('reset.password.email');

// Route hiển thị form đặt lại mật khẩu (nếu bạn sử dụng giao diện)
Route::get('/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset.form');

// Route thực hiện đặt lại mật khẩu
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

//Quên mật khẩu
Route::get('/forgot-password', [ResetPasswordController::class, 'showForgotForm'])->name('password.forgot');

Route::delete('/admin/user/{id}', [AdminController::class, 'deleteUser'])->name('admin.deleteUser');
Route::delete('/admin/song/{id}', [AdminController::class, 'deleteSong'])->name('admin.deleteSong');
Route::patch('/admin/song/{id}/status', [AdminController::class, 'updateStatus'])->name('admin.updateStatus');
Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/users', [AdminController::class, 'manageUsers'])->name('manageUsers');
    Route::get('/authors', [AdminController::class, 'manageAuthors'])->name('manageAuthors');
    Route::get('/get-authors', [AuthorController::class, 'index'])->name('authors');
    Route::post('/create-author', [AuthorController::class, 'createAuthor']);
    Route::get('/get-users', [UserController::class, 'index'])->name('users');
    Route::post('/users/{user}/ban', [UserController::class, 'banUser'])->name('users.ban');
    Route::post('/users/{user}/unban', [UserController::class, 'unbanUser'])->name('users.unban');

    // Hiển thị danh sách bài hát với bộ lọc
    Route::get('/songs', [AdminSongsController::class, 'index'])->name('songs');
    Route::post('/songs/create', [AdminSongsController::class, 'create'])->name('createSong');

    // Cập nhật trạng thái bài hát
    Route::patch('/songs/{id}/status', [AdminSongsController::class, 'updateStatus'])->name('updateStatus');

    // Hiển thị popup chỉnh sửa bài hát
    Route::get('/songs/{id}/edit', [AdminSongsController::class, 'edit'])->name('editSong');

    // Xử lý lưu bài hát sau chỉnh sửa
    Route::put('/songs/{id}', [AdminSongsController::class, 'update'])->name('updateSong');

    // Duyệt bài hát
    Route::get('/song-approval', [SongApprovalController::class, 'index'])->name('songApproval');
    Route::post('/song-approval/{id}/approve', [SongApprovalController::class, 'approve'])->name('songApproval.approve');
    Route::post('/song-approval/{id}/reject', [SongApprovalController::class, 'reject'])->name('songApproval.reject');
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

Route::prefix('momo')->name('momo.')->group(function () {
    Route::post('/momo-payment', [PaymentWithMomoController::class, 'createPayment'])->name('momo');
    Route::get('/momo-return', [PaymentWithMomoController::class, 'return'])->name('return');
    Route::post('/momo-notify', [PaymentWithMomoController::class, 'notify'])->name('notify');


});

Route::post('upload-image', [ImageController::class, 'storeImage'])->name('upload-image');

Route::get('image/{id}', [ImageController::class, 'showImage']);

// Đường dẫn tới manifest .mpd
Route::get('/storage/dash/{file}', [AudioController::class, 'playAudio']);

// Đường dẫn tới các segment .m4s
Route::get('/storage/dash/segment/{file}', [AudioController::class, 'streamSegment']);

//Uplaod nhạc bản quyền và sinh fingerprint
Route::post('/upload/fingerprint', [SongController::class, 'uploadAndGenerateFingerprint'])->name('upload.fingerprint');

Route::get('/layoutsetting', function () {
    return view('setting/layoutsetting');
});
