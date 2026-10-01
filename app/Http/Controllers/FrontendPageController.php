<?php

namespace App\Http\Controllers;

use App\Models\Investment;
use App\Models\InvestmentPost;
use App\Models\Page;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FrontendPageController extends Controller
{
    /**
     * Shared data passed to every frontend page view.
     */
    private function sharedData(string $slug): array
    {
        $setting     = Setting::getSiteSettings();
        $companyName = $setting->company_name ?? 'InvestHub';
        $page        = Page::getPage($slug);
        $content     = $page->content ?? Page::defaultContentFor($slug);

        return compact('setting', 'companyName', 'page', 'content');
    }

    /**
     * Helper to replace placeholders like {company}, {email}, {phone}
     */
    public static function interpolate(string $text, $setting, string $companyName): string
    {
        return str_replace(
            ['{company}', '{email}', '{phone}', '{address}'],
            [
                $companyName,
                $setting->email ?? 'info@investhub.com',
                $setting->phone ?? '+880 1700-000000',
                $setting->address ?? 'Dhaka, Bangladesh',
            ],
            $text
        );
    }

    /**
     * About Us page.
     */
    public function about(): View
    {
        $data = $this->sharedData('about');

        // Dynamic Real Database Stats
        $investorsCount = User::where(function ($query) {
            $query->whereHas('roles', function ($roleQuery) {
                $roleQuery->where('name', 'investor');
            })->orWhereDoesntHave('roles');
        })->count();
        if ($investorsCount < 1) {
            $investorsCount = User::count();
        }

        $productsCount = InvestmentPost::count();
        $totalInvested = (float) Investment::whereIn('status', ['approved', 'active', 'completed'])->sum('investment_amount');
        if ($totalInvested <= 0) {
            $totalInvested = (float) InvestmentPost::sum('target_amount');
        }

        // Format total invested to readable Bangladeshi Lakh/Crore or standard number
        if ($totalInvested >= 10000000) {
            $formattedInvested = '৳' . round($totalInvested / 10000000, 1) . ' Cr+';
        } elseif ($totalInvested >= 100000) {
            $formattedInvested = '৳' . round($totalInvested / 100000, 1) . ' Lakh+';
        } elseif ($totalInvested > 0) {
            $formattedInvested = '৳' . number_format($totalInvested);
        } else {
            $formattedInvested = '৳100%';
        }

        $dbStats = [
            'investors'       => max(1, $investorsCount),
            'products'        => max(1, $productsCount),
            'total_invested'  => $formattedInvested,
            'years'           => max(1, (int)date('Y') - 2023) . '+ Years',
        ];

        return view('frontend.pages.about', array_merge($data, compact('dbStats')));
    }

    /**
     * Contact Us page.
     */
    public function contact(): View
    {
        return view('frontend.pages.contact', $this->sharedData('contact'));
    }

    /**
     * Handle contact form submission.
     */
    public function submitContact(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:120',
            'email'   => 'required|email|max:255',
            'phone'   => 'nullable|string|max:30',
            'subject' => 'required|string|max:150',
            'message' => 'required|string|min:10|max:2000',
        ]);

        \App\Models\Contact::create([
            'name'       => $request->input('name'),
            'email'      => $request->input('email'),
            'phone'      => $request->input('phone'),
            'subject'    => $request->input('subject'),
            'message'    => $request->input('message'),
            'ip_address' => $request->ip(),
            'status'     => 'unread',
        ]);

        return redirect()
            ->route('page.contact')
            ->with('contact_success', 'Thank you, ' . $request->name . '! Your message has been received. We will get back to you shortly.');
    }

    /**
     * Privacy Policy page.
     */
    public function privacy(): View
    {
        return view('frontend.pages.privacy', $this->sharedData('privacy'));
    }

    /**
     * Terms & Conditions page.
     */
    public function terms(): View
    {
        return view('frontend.pages.terms', $this->sharedData('terms'));
    }

    /**
     * Active Opportunities public page.
     */
    public function opportunities(Request $request): View
    {
        $data = $this->sharedData('opportunities');

        $query = InvestmentPost::withCount([
            'investments as member_count' => function ($q) {
                $q->whereIn('status', ['pending', 'active', 'sold', 'completed', 'approved']);
            }
        ])->latest();

        // Optional filter
        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        $posts = $query->get();

        // Distinct available categories from real DB
        $availableTypes = InvestmentPost::whereNotNull('type')
            ->distinct()
            ->pluck('type')
            ->toArray();

        return view('frontend.pages.opportunities', array_merge(
            $data,
            compact('posts', 'availableTypes')
        ));
    }
}

