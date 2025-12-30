<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Anime\AnimeController;
use App\Http\Controllers\Auth\FirebaseAuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Billing\MidtransCallbackController;
use App\Http\Controllers\Billing\PaymentController;
use App\Http\Controllers\Stream\StreamProxyController;
use App\Http\Controllers\Stream\Watch\WatchController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WatchlistController;
use App\Http\Controllers\Admin\ReportController;
use App\Models\Subscription;
use App\Http\Controllers\Admin\UserController;



// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index']);

// Anime browsing & streaming
Route::get('/anime', [AnimeController::class, 'index'])->name('anime.index');
Route::get('/anime/{id}', [AnimeController::class, 'show'])->name('anime.show');

Route::middleware(['auth', 'premium'])->group(function () {
Route::get('/watch/{id}/{episode}', [WatchController::class, 'show'])->name('watch.show');
});
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

    Route::get('/watchlist', [WatchlistController::class, 'index'])->name('watchlist');
    Route::post('/watchlist', [WatchlistController::class, 'store'])->name('watchlist.store');
    Route::delete('/watchlist/{identifier}', [WatchlistController::class, 'destroy'])->name('watchlist.destroy');

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
    Route::get('/dashboard', [AdminUserController::class, 'dashboard'])->name('dashboard');
    Route::get('/admin/users/{user}', [AdminUserController::class, 'show'])->name('admin.users.show');
    Route::get('/admin/users/{user}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');

    Route::get('/admin/reports/revenue', [ReportController::class, 'revenue'])->name('admin.reports.revenue');

    Route::resource('admin/subscriptions', \App\Http\Controllers\Admin\SubscriptionController::class)->names([
        'index' => 'admin.subscriptions.index',
        'create' => 'admin.subscriptions.create',
        'store' => 'admin.subscriptions.store',
        'show' => 'admin.subscriptions.show',
        'edit' => 'admin.subscriptions.edit',
        'update' => 'admin.subscriptions.update',
        'destroy' => 'admin.subscriptions.destroy',
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
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('auth.profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('auth.profile.update');
    Route::delete('/profile/avatar', [ProfileController::class, 'destroyAvatar'])->name('auth.profile.destroy-avatar');
    Route::delete('/profile/banner', [ProfileController::class, 'destroyBanner'])->name('auth.profile.destroy-banner');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('auth.profile.destroy');

});
// Route::get('/pricing', fn () => view('auth.pricing'))->name('pages.pricing');
// Route::get('/checkout/{plan}', fn ($plan) => view('auth.checkout', compact('plan')))
//     ->name('pages.checkout');
// Route::get('/settings', fn () => view('auth.settings'))->middleware('auth')->name('auth.settings');

Route::post('/watchlist', [WatchlistController::class, 'store'])->middleware('auth')->name('watchlist.store');

