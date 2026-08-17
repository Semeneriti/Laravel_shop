<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProductController;
use App\Jobs\SendWelcomeAfterVerificationJob; // 👈 ДОБАВЛЕНО
use Illuminate\Support\Facades\Route;

Route::view('/', 'main')->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register.form');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile.form');
    Route::patch('/profile/{id}', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::get('/change-password', [AuthController::class, 'showChangePasswordForm'])->name('password.form');
    Route::post('/change-password', [AuthController::class, 'updatePassword'])->name('password.update');
});

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

Route::get('/categories', [\App\Http\Controllers\CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category:slug}', [\App\Http\Controllers\CategoryController::class, 'show'])->name('categories.show');

Route::get('/cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
Route::post('/cart/items/{product}', [\App\Http\Controllers\CartController::class, 'store'])->name('cart.items.store');
Route::patch('/cart/items/{product}', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.items.update');
Route::delete('/cart/items/{product}', [\App\Http\Controllers\CartController::class, 'destroy'])->name('cart.items.destroy');
Route::delete('/cart', [\App\Http\Controllers\CartController::class, 'clear'])->name('cart.clear');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders', [\App\Http\Controllers\OrderController::class, 'store'])->name('orders.store');
    Route::patch('/orders/{order}/status', [\App\Http\Controllers\OrderController::class, 'updateStatus'])
        ->name('orders.status.update');
});

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
        Route::patch('users/{user}/password', [\App\Http\Controllers\Admin\UserController::class, 'resetPassword'])->name('users.password');
        Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class);
        Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class);
    });

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (Illuminate\Http\Request $request, $id, $hash) {
    $user = \App\Models\User::findOrFail($id);
    if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        abort(403);
    }
    if ($user->hasVerifiedEmail()) {
        return redirect()->route('home')->with('info', 'Email уже подтверждён.');
    }

    $wasVerified = $user->markEmailAsVerified(); // 👈 ИЗМЕНЕНО

    if ($wasVerified) {
        SendWelcomeAfterVerificationJob::dispatch($user->id); // 👈 ДОБАВЛЕНО
    }

    event(new Illuminate\Auth\Events\Verified($user));
    return redirect()->route('home')->with('success', 'Email подтверждён!');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Illuminate\Http\Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('success', 'Ссылка для подтверждения отправлена.');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');
