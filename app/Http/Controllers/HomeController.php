<?php

namespace App\Http\Controllers;

use App\Models\InvestmentPost;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Investment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Public Landing Page.
     */
    public function index(): View
    {
        $posts = InvestmentPost::withCount([
                'investments as member_count' => function ($q) {
                    $q->whereIn('status', ['pending','active','sold','completed']);
                }
            ])
            ->whereIn('status', ['active', 'upcoming', 'imported'])
            ->get();
        return view('frontend.index', compact('posts'));
    }

    /**
     * Show Opportunity Details Page.
     */
    public function show($id): View
    {
        $post = InvestmentPost::withCount([
                'investments as member_count' => function ($q) {
                    $q->whereIn('status', ['pending','active','sold','completed']);
                }
            ])
            ->with('investments')
            ->findOrFail($id);

        $relatedPosts = InvestmentPost::where('id', '!=', $id)
            ->whereIn('status', ['active', 'upcoming'])
            ->take(3)
            ->get();

        return view('frontend.show', compact('post', 'relatedPosts'));
    }
    public function investmentsStore(Request $request)
    {
        if (!Auth::check()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please log in to place an investment.',
                    'redirect' => route('login'),
                ], 401);
            }
            return redirect()->route('login')->with('error', 'Please log in to place an investment.');
        }

        $request->validate([
            'investment_post_id' => 'required|exists:investment_posts,id',
            'amount'             => 'required|numeric|min:100',
        ]);

        $post = InvestmentPost::findOrFail($request->investment_post_id);

        // Block bids on sold-out posts
        if ($post->status === 'sold_out') {
            $errMsg = 'This investment opportunity is no longer accepting bids.';
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $errMsg], 422);
            }
            return back()->with('error', $errMsg);
        }

        if ((float) $request->amount < (float) $post->min_investment_amount) {
            $errMsg = 'Minimum investment amount for ' . $post->title . ' is ৳' . number_format($post->min_investment_amount);
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $errMsg], 422);
            }
            return back()->with('error', $errMsg);
        }

        return DB::transaction(function () use ($request, $post) {
            $user   = Auth::user();
            $amount = (float) $request->amount;

            // Calculate shares and expected profit
            $unitCost      = (float) $post->unit_cost ?: 1;
            // Use submitted per_piece_profit if provided, otherwise fall back to post's profit_per_unit
            $profitPerUnit = $request->filled('per_piece_profit')
                ? (float) $request->per_piece_profit
                : (float) $post->profit_per_unit;
            $quantityShare  = max(1, (int) round($amount / $unitCost));
            $expectedProfit = $quantityShare * $profitPerUnit;

            // Create investment bid with 'pending' status — admin must approve
            Investment::create([
                'user_id'                  => $user->id,
                'investment_post_id'       => $post->id,
                'investment_amount'        => $amount,
                'paid_amount'              => 0,
                'due_amount'               => $amount,
                'calculated_quantity_share'=> $quantityShare,
                'per_piece_profit'         => $profitPerUnit,
                'expected_profit'          => $expectedProfit,
                'status'                   => 'pending',
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Bid of ৳' . number_format($amount) . ' placed successfully! Awaiting admin approval.',
                    'redirect' => route('dashboard'),
                ]);
            }

            return redirect()->route('dashboard')->with('success', 'Your bid of ৳' . number_format($amount) . ' has been submitted and is awaiting admin approval.');
        });
    }
}
