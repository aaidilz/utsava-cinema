<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnimeApiController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
Route::get('/', function () {
    return view('welcome');
});


#pastiin harus pake middleware auth biar ga sembarangan orang bisa akses :D
// Anime API Routes
Route::prefix('api')->group(function () {
    Route::get('/search', [AnimeApiController::class, 'search']);
    Route::get('/episodes', [AnimeApiController::class, 'episodes']);
    Route::get('/watch', [AnimeApiController::class, 'watch']);
    Route::post('/cache/clear', [AnimeApiController::class, 'clearCache']);
});

Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/watch', function () {
    return view('auth.watchlist');
});



Route::get('/dashboard', function () {
    return view('auth.dashboard');
});

route::get('/home', function () {
    return view('Halamanutama.home');
});