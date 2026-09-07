<?php

namespace App\Http\Controllers;

use App\Models\Investment;
use App\Models\InvestmentPost;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class InvestmentController extends Controller
{
    /**
     * Display a listing of investments (admin sees all; investor sees own).
     */
    public function index(Request $request): View
    {
        $user    = Auth::user();
        $isAdmin = $user->hasRole(['admin', 'superadmin']) || $user->can('view all investments');

        $query = Investment::with(['user', 'post', 'payments'])->latest();

        if (!$isAdmin) {
            $query->where('user_id', $user->id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('post', function ($pq) use ($search) {
                    $pq->where('title', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by specific investment post (e.g. from member count badge click)
        $postId = $request->input('investment_post_id', $request->input('post_id'));
        if (!empty($postId)) {
            $query->where('investment_post_id', $postId);
        }

        $investments = $query->paginate(10)->withQueryString();
        $users       = $isAdmin ? User::orderBy('name')->get() : collect([$user]);
        $posts       = InvestmentPost::latest()->get();

        return view('backend.investments.index', compact('investments', 'users', 'posts', 'isAdmin'));
    }

    /**
     * Show form for creating a new investment manually.
     */
    public function create(): View
    {
        $users = User::orderBy('name')->get();
        $posts = InvestmentPost::whereIn('status', ['active', 'upcoming', 'imported'])->get();
        return view('backend.investments.create', compact('users', 'posts'));
    }

    /**
     * Store a newly created investment (Admin creates Active; Investor creates Pending).
     */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $isAdmin = $user->hasRole(['admin', 'superadmin']) || $user->can('view all investments');

        $rules = [
            'investment_post_id' => 'required|exists:investment_posts,id',
            'amount'             => 'required|numeric|min:1',
        ];

        if ($isAdmin) {
            $rules['user_id'] = 'required|exists:users,id';
        }

        $validated = $request->validate($rules);

        $investorId = $isAdmin ? $validated['user_id'] : $user->id;
        $status     = $isAdmin ? 'active' : 'pending';

        $post = InvestmentPost::findOrFail($validated['investment_post_id']);

        // Check if post is active
        if ($post->status === 'sold_out') {
            return back()->with('error', 'This investment opportunity is sold out and no longer accepting investments.')->withInput();
        }

        if (!$isAdmin && (float) $validated['amount'] < (float) $post->min_investment_amount) {
            return back()->with('error', 'Minimum investment amount for ' . $post->title . ' is ৳' . number_format($post->min_investment_amount))->withInput();
        }

        $unitCost      = (float) $post->unit_cost ?: 1;
        $profitPerUnit = (float) $post->profit_per_unit;

        $quantityShare  = max(1, (int) round($validated['amount'] / $unitCost));
        $expectedProfit = $quantityShare * $profitPerUnit;

        DB::transaction(function () use ($investorId, $validated, $post, $quantityShare, $profitPerUnit, $expectedProfit, $status, $isAdmin) {
            $amount = (float) $validated['amount'];
            $investment = Investment::create([
                'user_id'                   => $investorId,
                'investment_post_id'        => $validated['investment_post_id'],
                'investment_amount'         => $amount,
                'paid_amount'               => 0,
                'due_amount'                => $amount,
                'calculated_quantity_share' => $quantityShare,
                'per_piece_profit'          => $profitPerUnit,
                'expected_profit'           => $expectedProfit,
                'status'                    => $status,
            ]);

            // If admin creates as active, update post current_invested_amount
            if ($status === 'active') {
                $post->increment('current_invested_amount', $amount);
            }
        });

        $msg = $isAdmin 
            ? 'Investment created successfully with Active status.' 
            : 'Your investment request of ৳' . number_format($validated['amount']) . ' has been submitted and is awaiting admin approval.';

        return redirect()->route('investments.index')->with('success', $msg);
    }

    /**
     * Show form for editing an investment.
     */
    public function edit(Investment $investment): View
    {
        $users = User::orderBy('name')->get();
        $posts = InvestmentPost::all();
        return view('backend.investments.edit', compact('investment', 'users', 'posts'));
    }

    /**
     * Update the specified investment.
     * - pending → active: auto-create Payment record for the investor.
     * - * → sold: mark the investment post as sold_out.
     */
    public function update(Request $request, Investment $investment): RedirectResponse
    {
        $validated = $request->validate([
            'amount'                    => 'required|numeric|min:1',
            'calculated_quantity_share' => 'required|integer|min:1',
            'per_piece_profit'          => 'nullable|numeric|min:0',
            'expected_profit'           => 'nullable|numeric|min:0',
            'status'                    => 'required|in:pending,active,sold,completed,cancelled,refunded',
        ]);

        $profitPerUnit = isset($validated['per_piece_profit']) && $validated['per_piece_profit'] > 0
            ? (float) $validated['per_piece_profit']
            : (float) ($investment->per_piece_profit ?: ($investment->post->profit_per_unit ?? 0));

        $newAmount = (float) $validated['amount'];
        $validated['investment_amount'] = $newAmount;
        $validated['per_piece_profit']  = $profitPerUnit;
        $validated['expected_profit']   = $validated['calculated_quantity_share'] * $profitPerUnit;
        $validated['due_amount']        = max(0, $newAmount - (float) $investment->paid_amount);

        $oldStatus = $investment->status;
        $newStatus = $validated['status'];

        DB::transaction(function () use ($validated, $investment, $oldStatus, $newStatus) {
            $investment->update($validated);

            $post = $investment->post;

            // If transitioning to 'active' (admin approved) — update post invested amount
            if ($oldStatus !== 'active' && $newStatus === 'active') {
                if ($post) {
                    $post->increment('current_invested_amount', $investment->investment_amount);
                }
            }

            // If transitioning to 'sold' — mark post as sold_out
            if ($newStatus === 'sold' && $post) {
                $post->update(['status' => 'sold_out']);
            }

            // If moved away from active/sold back to pending/cancelled — rollback invested amount
            if (in_array($oldStatus, ['active', 'sold']) && in_array($newStatus, ['pending', 'cancelled', 'refunded']) && $post) {
                $post->decrement('current_invested_amount', $investment->investment_amount);
                // Revert post sold_out if needed
                if ($post->status === 'sold_out' && $newStatus !== 'sold') {
                    $post->update(['status' => 'active']);
                }
            }
        });

        return redirect()->route('investments.index')->with('success', 'Investment updated successfully.');
    }

    /**
     * Quick approve: set investment status to active.
     */
    public function approve(Investment $investment): RedirectResponse
    {
        if ($investment->status !== 'pending') {
            return redirect()->route('investments.index')->with('error', 'Only pending bids can be approved.');
        }

        DB::transaction(function () use ($investment) {
            $investment->update(['status' => 'active']);
            $post = $investment->post;
            if ($post) {
                $post->increment('current_invested_amount', $investment->investment_amount);
            }
        });

        return redirect()->route('investments.index')->with('success', 'Investment bid approved successfully.');
    }

    /**
     * Quick reject: set investment status to cancelled.
     */
    public function reject(Investment $investment): RedirectResponse
    {
        if ($investment->status !== 'pending') {
            return redirect()->route('investments.index')->with('error', 'Only pending bids can be rejected.');
        }

        $investment->update(['status' => 'cancelled']);

        return redirect()->route('investments.index')->with('success', 'Investment bid rejected.');
    }

    /**
     * Remove the specified investment.
     */
    public function destroy(Investment $investment): RedirectResponse
    {
        if (in_array($investment->status, ['active', 'sold', 'completed'])) {
            return redirect()->route('investments.index')->with('error', 'Approved investments cannot be deleted.');
        }

        $investment->delete();
        return redirect()->route('investments.index')->with('success', 'Investment deleted successfully.');
    }

    /**
     * Create a payment record when a bid is approved (status → active).
     */
    private function createPaymentForInvestment(Investment $investment, ?InvestmentPost $post): void
    {
        // Only create if no payment already exists for this investment
        $alreadyPaid = Payment::where('member_id', $investment->user_id)
            ->where('message', 'like', '%Investment in ' . ($post->title ?? '') . '%')
            ->where('paid_amount', $investment->investment_amount)
            ->exists();

        if (!$alreadyPaid) {
            Payment::create([
                'member_id'          => $investment->user_id,
                'investment_id'      => $investment->id,
                'investment_post_id' => $investment->investment_post_id,
                'payment_number'     => 'INV-' . strtoupper(Str::random(8)),
                'paid_amount'        => $investment->investment_amount,
                'payment_date'       => now(),
                'message'            => 'Investment in ' . ($post->title ?? 'Unknown Post'),
                'status'             => 'approved',
            ]);
            $investment->recalculatePaidAndDue();
        }
    }
}
