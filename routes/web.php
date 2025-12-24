<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnimeApiController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AnimeController;
use App\Http\Controllers\StreamProxyController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;

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

// Public dashboard (accessible tanpa login)
Route::get('/dashboard', [UserController::class, 'index'])->name('dashboard');


// Admin only routes
Route::middleware(['auth', 'admin'])->group(function () {
    // Dashboard handled by admin UserController@index (shows users + CRUD)
    Route::get('/dashboard', [UserController::class, 'index'])->name('dashboard');

    // Full admin users CRUD
    Route::resource('admin/users', UserController::class)->names([
        'index' => 'admin.users.index',
        'create' => 'admin.users.create',
        'store' => 'admin.users.store',
        'show' => 'admin.users.show',
        'edit' => 'admin.users.edit',
        'update' => 'admin.users.update',
        'destroy' => 'admin.users.destroy',
    ]);
});


// test routes for static pages
Route::get('/pricing', fn () => view('auth.pricing'))->name('pages.pricing');
Route::get('/checkout/{plan}', fn ($plan) => view('auth.checkout', compact('plan')))
    ->name('pages.checkout');
Route::get('/settings', fn () => view('auth.settings'))->middleware('auth')->name('auth.settings');
// Route::get('/pricing', fn () => view('auth.pricing'))->name('pages.pricing');
// Route::get('/checkout/{plan}', fn ($plan) => view('auth.checkout', compact('plan')))
//     ->name('pages.checkout');
// Route::get('/settings', fn () => view('auth.settings'))->middleware('auth')->name('auth.settings');

// ...existing code...

Route::middleware('auth')->group(function () {
    // ...existing code...
    
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

