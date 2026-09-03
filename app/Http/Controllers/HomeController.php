<?php

namespace App\Http\Controllers;

use App\Models\InvestmentPost;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
}
