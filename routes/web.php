<?php

use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\InvestmentPostController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WithdrawalController;
use Illuminate\Support\Facades\Route;

Route::get('/cc', function () {
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    \Illuminate\Support\Facades\Artisan::call('route:clear');
    // \Illuminate\Support\Facades\Artisan::call('config:cache');
    // \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    return 'Cleared!';
});

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/opportunity/{id}', [HomeController::class, 'show'])->name('opportunity.show');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('user-password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Public / Client Investment Action
    Route::post('/investments/place', [HomeController::class, 'investmentsStore'])->name('investments.store');
    Route::post('/investments/pay', [PaymentController::class, 'storeUserPayment'])->name('investments.pay');

    // Backend Admin Investment Manual Store
    Route::post('/investments/admin-store', [InvestmentController::class, 'store'])->name('investments.admin_store');

    // Backend CRUD Routes
    Route::resource('posts', InvestmentPostController::class);
    Route::resource('investments', InvestmentController::class)->except(['store']);
    Route::post('/investments/{investment}/approve', [InvestmentController::class, 'approve'])->name('investments.approve');
    Route::post('/investments/{investment}/reject', [InvestmentController::class, 'reject'])->name('investments.reject');
    Route::post('/payments/{payment}/approve', [PaymentController::class, 'approve'])->name('payments.approve');
    Route::post('/payments/{payment}/reject', [PaymentController::class, 'reject'])->name('payments.reject');
    Route::resource('payments', PaymentController::class)->except(['edit', 'update']);
    Route::resource('withdrawals', WithdrawalController::class);
    Route::resource('users', UserController::class);

    // Settings CRUD Routes
    Route::get('/settings', [SettingController::class, 'edit'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    // Role & Permission Management Routes
    Route::resource('roles', RolePermissionController::class);
});

require __DIR__.'/auth.php';
