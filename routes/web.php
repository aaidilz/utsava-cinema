<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnimeApiController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AnimeController;
use App\Http\Controllers\StreamProxyController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\FirebaseAuthController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MidtransCallbackController;
use App\Models\Subscription;

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

    Route::post('/auth/firebase/verify', [FirebaseAuthController::class, 'verifyToken'])
        ->name('auth.firebase.verify');
    
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    Route::get('/watchlist', function () {
        return view('auth.watchlist');
    })->name('watchlist');

    Route::post('/payments/initiate', [PaymentController::class, 'initiate'])
        ->name('payments.initiate');

    Route::post('/payments/{transaction}/refresh', [PaymentController::class, 'refresh'])
        ->name('payments.refresh');

    Route::post('/payments/{transaction}/cancel', [PaymentController::class, 'cancel'])
        ->name('payments.cancel');
});

Route::post('/midtrans/callback', [MidtransCallbackController::class, 'handle'])
    ->name('midtrans.callback');

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
Route::get('/pricing', function () {
    $subscriptions = Subscription::query()
        ->where('is_active', true)
        ->orderBy('price')
        ->get();

    return view('auth.pricing', compact('subscriptions'));
})->name('pages.pricing');

Route::get('/checkout/{subscription}', function (Subscription $subscription) {
    abort_unless((bool) $subscription->is_active, 404);

    return view('auth.checkout', compact('subscription'));
})->middleware('auth')->name('pages.checkout');
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

