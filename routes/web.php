<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnimeApiController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AnimeController;
use App\Http\Controllers\StreamProxyController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index']);

// Anime browsing & streaming
Route::get('/anime', [AnimeController::class, 'index'])->name('anime.index');
Route::get('/anime/{id}', [AnimeController::class, 'show'])->name('anime.show');
Route::get('/watch/{id}/{episode}', [AnimeController::class, 'watch'])->name('watch.show');
Route::get('/search', [AnimeController::class, 'search'])->name('anime.search');

// Stream proxy for referer support
Route::get('/stream-proxy/{id}/{episode}', [StreamProxyController::class, 'proxy'])->name('stream.proxy');

// Guest only routes (redirect to home if already authenticated)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    Route::get('/watchlist', function () {
        return view('auth.watchlist');
    })->name('watchlist');
});

// Admin only routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', function () {
        return view('auth.dashboard');
    })->name('dashboard');
});

Route::get('/pricing', fn () => view('pricing'));
Route::get('/checkout/{plan}', fn ($plan) => view('checkout', compact('plan')))
    ->name('checkout');
Route::get('/settings', fn () => view('settings'))->middleware('auth');
