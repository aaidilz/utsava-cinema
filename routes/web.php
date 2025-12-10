<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnimeApiController;
use App\Http\Controllers\LoginController;
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
Route::post('/logout', [LoginController::class, 'logout']);
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');


