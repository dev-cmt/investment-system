<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Withdrawal;
use App\Models\User;
use App\Models\Investment;
use App\Models\InvestmentPost;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $isAdmin = $user->hasRole(['admin', 'superadmin']) || $user->can('view posts');

        // Investor Data
        $userInvestments = Investment::with(['post', 'latestPayment', 'payments'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $totalInvested = $userInvestments->sum('investment_amount');
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
            'total_invested' => Investment::sum('investment_amount'),
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
    }
}
