<?php

namespace App\Http\Controllers;

use App\Helpers\ImageHelper;
use App\Models\Investment;
use App\Models\InvestmentPost;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PaymentController extends Controller
{
    /**
     * Display a listing of payments.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $isAdmin = $user->hasRole(['admin', 'superadmin']) || $user->can('view all payments');

        $query = Payment::with(['member', 'post', 'investment.post'])->latest();

        if (!$isAdmin) {
            $query->where('member_id', $user->id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('payment_number', 'like', "%{$search}%")
                  ->orWhere('transaction_id', 'like', "%{$search}%")
                  ->orWhereHas('member', function ($mq) use ($search) {
                      $mq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->paginate(10)->withQueryString();
        $members = $isAdmin ? User::orderBy('name')->get() : collect([$user]);
        $userInvestments = $isAdmin ? collect() : Investment::with(['post', 'payments'])
            ->where('user_id', $user->id)
            ->whereIn('status', ['active', 'completed', 'sold'])
            ->latest()
            ->get();

        $posts = InvestmentPost::with(['investments.user', 'investments.payments'])->latest()->get();
        $postsWithInvestors = $posts->mapWithKeys(function ($p) {
            return [
                $p->id => $p->investments->map(function ($inv) {
                    $paid = (float) $inv->payments->where('status', 'approved')->sum('paid_amount');
                    $total = (float) ($inv->investment_amount ?? 0);
                    $due = max(0, $total - $paid);
                    return [
                        'user_id'       => $inv->user_id,
                        'user_name'     => $inv->user->name ?? 'User #' . $inv->user_id,
                        'user_email'    => $inv->user->email ?? '',
                        'amount'        => $total,
                        'paid_amount'   => $paid,
                        'due_amount'    => $due,
                        'investment_id' => $inv->id,
                    ];
                })->values()
            ];
        });

        return view('backend.payments.index', compact('payments', 'members', 'isAdmin', 'posts', 'postsWithInvestors', 'userInvestments'));
    }

    /**
     * Store a newly created payment in storage (Admin).
     */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();
        abort_unless($user->hasRole(['admin', 'superadmin']) || $user->can('view all payments'), 403);

        $validated = $request->validate([
            'member_id'          => 'required|exists:users,id',
            'investment_post_id' => 'nullable|exists:investment_posts,id',
            'investment_id'      => 'nullable|exists:investments,id',
            'paid_amount'        => 'required|numeric|min:1',
            'payment_date'       => 'required|date',
            'transaction_id'     => 'nullable|string|max:100',
            'message'            => 'nullable|string|max:500',
            'status'             => 'required|in:approved,pending,rejected,cancelled',
            'slip'               => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
        ]);

        if ($request->hasFile('slip')) {
            $validated['slip'] = ImageHelper::uploadImage($request->file('slip'), 'uploads/slips');
        }

        $validated['payment_number'] = 'PAY-' . strtoupper(Str::random(8));

        $payment = Payment::create($validated);

        if ($payment->investment) {
            $payment->investment->recalculatePaidAndDue();
        }

        return redirect()->route('payments.index')->with('success', 'Payment record created successfully.');
    }

    /**
     * Store a user-submitted payment for an approved investment.
     */
    public function storeUserPayment(Request $request): RedirectResponse
    {
        $request->validate([
            'investment_id'  => 'required|exists:investments,id',
            'paid_amount'    => 'required|numeric|min:1',
            'payment_date'   => 'required|date',
            'transaction_id' => 'nullable|string|max:100',
            'message'        => 'nullable|string|max:500',
            'slip'           => 'required|file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
        ]);

        $user = Auth::user();
        $investment = Investment::where('id', $request->investment_id)
            ->where('user_id', $user->id)
            ->whereIn('status', ['active', 'completed', 'sold'])
            ->firstOrFail();

        // Prevent payment submission if a payment slip is already awaiting verification
        if ($investment->payments()->where('status', 'pending')->exists()) {
            return redirect()->back()->with('error', 'A payment slip for this investment is already awaiting admin verification.');
        }

        // Validate that paid amount does not exceed remaining due
        $approvedPaid = (float) $investment->payments()->where('status', 'approved')->sum('paid_amount');
        $dueAmount    = max(0, (float) $investment->investment_amount - $approvedPaid);

        if ($dueAmount <= 0) {
            return redirect()->back()->with('error', 'This investment is already fully paid.');
        }

        if ((float) $request->paid_amount > ($dueAmount + 0.001)) {
            return redirect()->back()->withErrors([
                'paid_amount' => 'Payment amount cannot exceed the remaining due of ৳' . number_format($dueAmount, 2) . '.'
            ])->withInput()->with('error', 'Payment amount cannot exceed the remaining due of ৳' . number_format($dueAmount, 2) . '.');
        }

        $slipPath = null;
        if ($request->hasFile('slip')) {
            $slipPath = ImageHelper::uploadImage($request->file('slip'), 'uploads/slips');
        }

        Payment::create([
            'member_id'          => $user->id,
            'investment_id'      => $investment->id,
            'investment_post_id' => $investment->investment_post_id,
            'payment_number'     => 'PAY-' . strtoupper(Str::random(8)),
            'paid_amount'        => $request->paid_amount,
            'payment_date'       => $request->payment_date,
            'transaction_id'     => $request->transaction_id,
            'message'            => $request->message ?: ('Payment for Investment #' . $investment->id . ' (' . ($investment->post->title ?? 'Opportunity') . ')'),
            'slip'               => $slipPath,
            'status'             => 'pending',
        ]);

        return redirect()->back()->with('success', 'Payment of ৳' . number_format($request->paid_amount, 2) . ' submitted successfully! Your receipt is pending admin verification.');
    }

    /**
     * Approve the specified payment (Admin).
     */
    public function approve(Payment $payment): RedirectResponse
    {
        $user = Auth::user();
        abort_unless($user->hasRole(['admin', 'superadmin']) || $user->can('view all payments'), 403);

        $payment->update(['status' => 'approved']);

        if ($payment->investment) {
            $payment->investment->recalculatePaidAndDue();
        }

        return redirect()->route('payments.index')->with('success', 'Payment #' . $payment->payment_number . ' approved successfully.');
    }

    /**
     * Reject the specified payment (Admin).
     */
    public function reject(Payment $payment): RedirectResponse
    {
        $user = Auth::user();
        abort_unless($user->hasRole(['admin', 'superadmin']) || $user->can('view all payments'), 403);

        $payment->update(['status' => 'rejected']);

        if ($payment->investment) {
            $payment->investment->recalculatePaidAndDue();
        }

        return redirect()->route('payments.index')->with('success', 'Payment #' . $payment->payment_number . ' rejected.');
    }

    /**
     * Update the specified payment in storage (Disabled - Payment records are immutable).
     */
    public function update(Request $request, Payment $payment): RedirectResponse
    {
        return redirect()->route('payments.index')->with('error', 'Payment records cannot be edited.');
    }

    /**
     * Remove the specified payment from storage.
     * Approved payment records cannot be deleted.
     */
    public function destroy(Payment $payment): RedirectResponse
    {
        $user = Auth::user();
        abort_unless($user->hasRole(['admin', 'superadmin']) || $user->can('view all payments'), 403);

        if ($payment->status === 'approved') {
            return redirect()->route('payments.index')->with('error', 'Approved payment records cannot be deleted.');
        }

        $inv = $payment->investment;

        if ($payment->slip) {
            ImageHelper::deleteImage($payment->slip);
        }

        $payment->delete();

        if ($inv) {
            $inv->recalculatePaidAndDue();
        }

        return redirect()->route('payments.index')->with('success', 'Payment record deleted successfully.');
    }
}
