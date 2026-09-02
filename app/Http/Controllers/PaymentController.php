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
        $userInvestments = $isAdmin ? collect() : Investment::with('post')
            ->where('user_id', $user->id)
            ->whereIn('status', ['active', 'completed'])
            ->latest()
            ->get();

        $posts = InvestmentPost::with(['investments.user'])->latest()->get();
        $postsWithInvestors = $posts->mapWithKeys(function ($p) {
            return [
                $p->id => $p->investments->map(function ($inv) {
                    return [
                        'user_id'       => $inv->user_id,
                        'user_name'     => $inv->user->name ?? 'User #' . $inv->user_id,
                        'user_email'    => $inv->user->email ?? '',
                        'amount'        => $inv->amount,
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

        Payment::create($validated);

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
            ->whereIn('status', ['active', 'completed'])
            ->firstOrFail();

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

        return redirect()->back()->with('success', 'Payment submitted successfully! Your receipt/slip is pending admin verification.');
    }

    /**
     * Update the specified payment in storage.
     */
    public function update(Request $request, Payment $payment): RedirectResponse
    {
        abort_unless($user = Auth::user(), 401);
        abort_unless($user->hasRole(['admin', 'superadmin']) || $user->can('view all payments'), 403);

        $validated = $request->validate([
            'paid_amount'    => 'required|numeric|min:1',
            'payment_date'   => 'required|date',
            'transaction_id' => 'nullable|string|max:100',
            'message'        => 'nullable|string|max:500',
            'status'         => 'required|in:approved,pending,rejected,cancelled',
            'slip'           => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
        ]);

        if ($request->hasFile('slip')) {
            $validated['slip'] = ImageHelper::uploadImage($request->file('slip'), 'uploads/slips', $payment->slip);
        }

        $payment->update($validated);

        return redirect()->route('payments.index')->with('success', 'Payment record updated successfully.');
    }

    /**
     * Remove the specified payment from storage.
     */
    public function destroy(Payment $payment): RedirectResponse
    {
        $user = Auth::user();
        abort_unless($user->hasRole(['admin', 'superadmin']) || $user->can('view all payments'), 403);

        if ($payment->slip) {
            ImageHelper::deleteImage($payment->slip);
        }

        $payment->delete();
        return redirect()->route('payments.index')->with('success', 'Payment record deleted successfully.');
    }
}
