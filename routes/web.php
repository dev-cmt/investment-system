<?php

use App\Http\Controllers\InvestmentAdminController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\InvestmentPostController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use App\Models\Investment;
use App\Models\InvestmentPost;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FrontendController;

use App\Http\Controllers\WithdrawalController;
use App\Models\Withdrawal;
use App\Models\User;

// Public Landing Page (Dynamic Investment Opportunities)
Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::get('/opportunity/{id}', [FrontendController::class, 'show'])->name('opportunity.show');

// Dashboard Route (Role & Permission Scoped)
Route::get('/dashboard', function () {
    $user = Auth::user();
    $isAdmin = $user->hasRole(['admin', 'superadmin']) || $user->can('view posts');
    
    // Investor Data
    $userInvestments = Investment::with(['post', 'latestPayment', 'payments'])
        ->where('user_id', $user->id)
        ->latest()
        ->get();

    $totalInvested = $userInvestments->sum('amount');
    $totalExpectedProfit = $userInvestments->sum('expected_profit');
    $activeCount = $userInvestments->where('status', 'active')->count();

    $userPayments = Payment::where('member_id', $user->id)
        ->latest()
        ->get();

    $userWithdrawals = Withdrawal::where('user_id', $user->id)
        ->latest()
        ->get();

    $totalWithdrawn = $userWithdrawals->whereIn('status', ['approved', 'completed'])->sum('amount');

    $allPosts = InvestmentPost::latest()->get();

    // System-wide Admin Data
    $systemStats = [
        'total_invested' => Investment::sum('amount'),
        'total_investors' => User::role('investor')->count() ?: User::count(),
        'active_posts' => InvestmentPost::where('status', 'active')->count(),
        'pending_withdrawals' => Withdrawal::where('status', 'pending')->count(),
        'total_paid' => Payment::where('status', 'approved')->sum('paid_amount'),
    ];

    $recentInvestments = Investment::with(['user', 'post'])->latest()->take(5)->get();
    $recentWithdrawals = Withdrawal::with('user')->latest()->take(5)->get();

    return view('backend.dashboard', compact(
        'user',
        'isAdmin',
        'userInvestments',
        'totalInvested',
        'totalExpectedProfit',
        'activeCount',
        'userPayments',
        'userWithdrawals',
        'totalWithdrawn',
        'allPosts',
        'systemStats',
        'recentInvestments',
        'recentWithdrawals'
    ));
})->middleware(['auth', 'verified'])->name('dashboard');

// Protected Routes
Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('user-password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Public / Client Investment Action
    Route::post('/investments/place', [InvestmentController::class, 'store'])->name('investments.store');
    Route::post('/investments/pay', [PaymentController::class, 'storeUserPayment'])->name('investments.pay');

    // Backend Admin Investment Manual Store
    Route::post('/investments/admin-store', [InvestmentAdminController::class, 'store'])->name('investments.admin_store');

    // Backend CRUD Routes
    Route::resource('posts', InvestmentPostController::class);
    Route::resource('investments', InvestmentAdminController::class)->except(['store']);
    Route::post('/investments/{investment}/approve', [InvestmentAdminController::class, 'approve'])->name('investments.approve');
    Route::post('/investments/{investment}/reject', [InvestmentAdminController::class, 'reject'])->name('investments.reject');
    Route::resource('payments', PaymentController::class);
    Route::resource('withdrawals', WithdrawalController::class);
    Route::resource('users', UserController::class);
    
    // Settings CRUD Routes
    Route::get('/settings', [SettingController::class, 'edit'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    // Role & Permission Management Routes
    Route::resource('roles', RolePermissionController::class);
});

require __DIR__.'/auth.php';
