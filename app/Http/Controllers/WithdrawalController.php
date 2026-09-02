<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class WithdrawalController extends Controller
{
    /**
     * Display a listing of withdrawal requests.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $isAdmin = $user->hasRole(['admin', 'superadmin']) || $user->can('view all withdrawals');

        $query = Withdrawal::with('user')->latest();

        // Scope for regular investors
        if (!$isAdmin) {
            $query->where('user_id', $user->id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('withdrawal_number', 'like', "%{$search}%")
                  ->orWhere('payment_method', 'like', "%{$search}%")
                  ->orWhere('account_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $withdrawals = $query->paginate(10)->withQueryString();
        $users = $isAdmin ? User::orderBy('name')->get() : collect();

        // Calculate stats
        $statsQuery = Withdrawal::query();
        if (!$isAdmin) {
            $statsQuery->where('user_id', $user->id);
        }

        $totalRequested = (clone $statsQuery)->sum('amount');
        $totalApproved = (clone $statsQuery)->whereIn('status', ['approved', 'completed'])->sum('amount');
        $pendingCount = (clone $statsQuery)->where('status', 'pending')->count();

        return view('backend.withdrawals.index', compact('withdrawals', 'users', 'isAdmin', 'totalRequested', 'totalApproved', 'pendingCount'));
    }

    /**
     * Store a newly created withdrawal request.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $isAdmin = $user->hasRole(['admin', 'superadmin']) || $user->can('manage withdrawals');

        $rules = [
            'amount' => 'required|numeric|min:10',
            'payment_method' => 'required|string|max:50',
            'account_number' => 'required|string|max:100',
            'account_name' => 'nullable|string|max:100',
            'bank_name' => 'nullable|string|max:100',
            'branch_name' => 'nullable|string|max:100',
            'user_note' => 'nullable|string|max:500',
        ];

        if ($isAdmin && $request->filled('user_id')) {
            $rules['user_id'] = 'required|exists:users,id';
            $targetUserId = $request->user_id;
        } else {
            $targetUserId = $user->id;
        }

        $validated = $request->validate($rules);
        $validated['user_id'] = $targetUserId;
        $validated['withdrawal_number'] = 'WD-' . strtoupper(Str::random(8));
        $validated['status'] = 'pending';

        Withdrawal::create($validated);

        return redirect()->route('withdrawals.index')->with('success', 'Withdrawal request submitted successfully.');
    }

    /**
     * Update status and notes for a withdrawal request.
     */
    public function update(Request $request, Withdrawal $withdrawal): RedirectResponse
    {
        $user = Auth::user();
        $isAdmin = $user->hasRole(['admin', 'superadmin']) || $user->can('manage withdrawals');

        if (!$isAdmin) {
            // Investor can only cancel their own pending withdrawal
            if ($withdrawal->user_id !== $user->id || $withdrawal->status !== 'pending') {
                return back()->with('error', 'You are not authorized to update this withdrawal request.');
            }

            $validated = $request->validate([
                'status' => 'required|in:cancelled',
            ]);

            $withdrawal->update($validated);
            return redirect()->route('withdrawals.index')->with('success', 'Withdrawal request cancelled.');
        }

        // Admin status update
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected,completed,cancelled',
            'admin_note' => 'nullable|string|max:500',
        ]);

        if (in_array($validated['status'], ['approved', 'completed']) && $withdrawal->status !== $validated['status']) {
            $validated['processed_at'] = now();
        }

        $withdrawal->update($validated);

        return redirect()->route('withdrawals.index')->with('success', 'Withdrawal status updated successfully.');
    }

    /**
     * Remove the specified withdrawal request.
     */
    public function destroy(Withdrawal $withdrawal): RedirectResponse
    {
        $user = Auth::user();
        $isAdmin = $user->hasRole(['admin', 'superadmin']) || $user->can('manage withdrawals');

        if (!$isAdmin && $withdrawal->user_id !== $user->id) {
            return back()->with('error', 'Unauthorized action.');
        }

        $withdrawal->delete();
        return redirect()->route('withdrawals.index')->with('success', 'Withdrawal record deleted successfully.');
    }
}
