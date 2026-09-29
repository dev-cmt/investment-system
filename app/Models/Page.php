<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
    ];

    protected $casts = [
        'content' => 'array',
    ];

    /**
     * Get or create a page by slug with default structure merged.
     */
    public static function getPage(string $slug = 'home'): self
    {
        $page = self::firstOrCreate(
            ['slug' => $slug],
            [
                'title' => ucfirst($slug) . ' Page',
                'content' => self::defaultContentFor($slug),
            ]
        );

        // Ensure default structure is merged if keys are missing
        $defaults = self::defaultContentFor($slug);
        $current = $page->content ?? [];
        $merged = array_replace_recursive($defaults, is_array($current) ? $current : []);

        if ($merged !== $current) {
            $page->content = $merged;
            $page->save();
        }

        return $page;
    }

    /**
     * Retrieve a specific section or key from content with fallback.
     */
    public function getSection(string $section, $default = null)
    {
        $content = $this->content ?? [];
        if (array_key_exists($section, $content)) {
            return $content[$section];
        }

        $defaults = self::defaultContentFor($this->slug ?? 'home');
        return $defaults[$section] ?? $default;
    }

    /**
     * Default schema and fallback content for pages.
     */
    public static function defaultContentFor(string $slug): array
    {
        if ($slug === 'home') {
            return self::defaultHomeContent();
        }

        return [];
    }

    /**
     * Default Home Page content structure.
     */
    public static function defaultHomeContent(): array
    {
        return [
            // ─── HERO SECTION ──────────────────────────────
            'hero' => [
                'show_badge'          => true,
                'badge_text'          => 'LIVE INVESTMENTS ACTIVE',
                'title'               => "Invest in Real Products\nEarn Weekly Profit",
                'highlight_text'      => 'Weekly Profit',
                'description'         => "We import high demand products from China.\nYou invest, we handle the rest and you earn\nweekly profit after sales.",
                'btn1_text'           => 'Explore Opportunities',
                'btn1_link'           => '#opportunities',
                'btn1_auth_text'      => 'Explore Opportunities',
                'btn1_guest_text'     => 'Sign Up to Invest',
                'btn2_text'           => 'How It Works',
                'btn2_link'           => '#howItWorks',
                'media_type'          => 'image', // 'image' or 'video'
                'image'               => 'images/hero.jpg',
                'video_url'           => '',
                'stat_card_1_icon'    => 'fas fa-arrow-trend-up',
                'stat_card_1_label'   => 'Total Investors',
                'stat_card_1_value'   => '1,240+',
                'stat_card_2_icon'    => 'fa-solid fa-shield-heart',
                'stat_card_2_label'   => 'Secured Investments',
                'stat_card_2_value'   => '100%',
                'trust_badges'        => [
                    [
                        'icon'     => 'fa-solid fa-shield-heart',
                        'title'    => '100% Secure',
                        'subtitle' => 'Your investment is safe',
                    ],
                    [
                        'icon'     => 'fas fa-clock',
                        'title'    => 'Weekly Profit',
                        'subtitle' => 'Paid every week',
                    ],
                    [
                        'icon'     => 'fas fa-truck-fast',
                        'title'    => 'Fast Import',
                        'subtitle' => 'Products in 25-30 days',
                    ],
                ],
            ],

            // ─── OPPORTUNITIES SECTION ──────────────────────
            'opportunities' => [
                'title'             => 'Active Investment Opportunities',
                'how_it_works_text' => 'How It Works',
                'empty_message'     => 'No active investment opportunities right now. Check back soon!',
            ],

            // ─── FEATURES STRIP ─────────────────────────────
            'features' => [
                'show_section' => true,
                'items' => [
                    [
                        'icon'  => 'fa-solid fa-shield-heart',
                        'title' => 'Safe & Secure',
                        'desc'  => 'Your investment is protected with full transparency.',
                    ],
                    [
                        'icon'  => 'fas fa-arrows-rotate',
                        'title' => 'Weekly Profit',
                        'desc'  => 'Profit will be paid every week after sales start.',
                    ],
                    [
                        'icon'  => 'fas fa-truck-fast',
                        'title' => 'Fast Delivery',
                        'desc'  => 'Products are imported within 25-30 days.',
                    ],
                    [
                        'icon'  => 'fas fa-headset',
                        'title' => 'Support',
                        'desc'  => 'We are here to help you 24/7.',
                    ],
                ],
            ],

            // ─── HOW IT WORKS ───────────────────────────────
            'how_it_works' => [
                'show_section'  => true,
                'section_label' => 'Step by Step',
                'title'         => 'How It Works',
                'subtitle'      => 'A transparent 6-step process from product post to profit',
                'steps'         => [
                    [
                        'num'   => '01',
                        'icon'  => 'fa-boxes-packing',
                        'title' => 'Product Posted',
                        'desc'  => 'We post high demand product investment opportunities with full details.',
                    ],
                    [
                        'num'   => '02',
                        'icon'  => 'fa-hand-holding-dollar',
                        'title' => 'Investor Invests',
                        'desc'  => 'One investor invests the full product amount.',
                    ],
                    [
                        'num'   => '03',
                        'icon'  => 'fa-truck-fast',
                        'title' => 'Product Imported',
                        'desc'  => 'We import the product from China. (Estimated 25 Days)',
                    ],
                    [
                        'num'   => '04',
                        'icon'  => 'fa-store',
                        'title' => 'Product Sold',
                        'desc'  => 'After arrival, product selling starts.',
                    ],
                    [
                        'num'   => '05',
                        'icon'  => 'fa-calendar-check',
                        'title' => 'Weekly Profit',
                        'desc'  => 'You will receive profit every week.',
                    ],
                    [
                        'num'   => '06',
                        'icon'  => 'fa-circle-check',
                        'title' => 'Completed',
                        'desc'  => 'After full profit & capital return, you can re-invest.',
                    ],
                ],
            ],

            // ─── WHY INVEST WITH US ─────────────────────────
            'why_invest' => [
                'show_section' => true,
                'title'        => 'Why Invest With Us?',
                'subtitle'     => 'Built for security, transparency, and consistent weekly returns',
                'cards'        => [
                    [
                        'icon'  => 'fa-shield-halved',
                        'color' => '#38BDF8',
                        'title' => 'Transparent Process',
                        'desc'  => 'Complete transparency in every import step, custom clearance, and sales reporting.',
                    ],
                    [
                        'icon'  => 'fa-wallet',
                        'color' => '#FACC15',
                        'title' => 'Weekly Profit',
                        'desc'  => 'Profit payouts every week directly to your bank or mobile wallet after sales start.',
                    ],
                    [
                        'icon'  => 'fa-lock',
                        'color' => '#4ADE80',
                        'title' => 'Secure Investment',
                        'desc'  => 'Single product & single investor model ensuring clear ownership and security.',
                    ],
                    [
                        'icon'  => 'fa-award',
                        'color' => '#C084FC',
                        'title' => 'Proven Track Record',
                        'desc'  => 'Successful imports & happy investors across multiple product batches.',
                    ],
                ],
            ],

            // ─── CALL TO ACTION / BANNER ────────────────────
            'cta' => [
                'show_section' => false,
                'title'        => 'Ready to Start Your Investment Journey?',
                'subtitle'     => 'Join our verified investors community and earn reliable weekly returns.',
                'btn_text'     => 'Create Free Account',
                'btn_link'     => '/register',
            ],

            // ─── SEO / META ─────────────────────────────────
            'seo' => [
                'meta_title'       => '',
                'meta_description' => '',
                'meta_keywords'    => '',
            ],
        ];
    }
}
