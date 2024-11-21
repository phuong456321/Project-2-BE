<?php

use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
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
