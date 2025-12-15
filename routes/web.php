<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnimeApiController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);


