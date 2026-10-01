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
        switch ($slug) {
            case 'home':
                return self::defaultHomeContent();
            case 'about':
                return self::defaultAboutContent();
            case 'contact':
                return self::defaultContactContent();
            case 'privacy':
                return self::defaultPrivacyContent();
            case 'terms':
                return self::defaultTermsContent();
            case 'opportunities':
                return self::defaultOpportunitiesContent();
            default:
                return [];
        }
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

    /**
     * Default About Page content structure.
     */
    public static function defaultAboutContent(): array
    {
        return [
            'hero' => [
                'show_badge'     => true,
                'badge_text'     => 'Our Company',
                'badge_icon'     => 'fas fa-building',
                'title'          => 'About {company}',
                'highlight_text' => '{company}',
                'description'    => 'We connect real investors with real import opportunities — delivering transparent weekly profits through a proven, secure investment model.',
            ],
            'story' => [
                'show_section'   => true,
                'label'          => 'Our Story',
                'title'          => "Built on Trust,\nDriven by Results",
                'image'          => 'images/hero.jpg',
                'badge_num'      => '100%',
                'badge_sub'      => 'Transparent Returns',
                'paragraphs'     => [
                    "{company} was founded with a simple vision: give everyday investors access to real, profitable import opportunities previously available only to large corporations. We source high-demand products from China and global markets, import them, sell them locally, and share the profits with our investors every week.",
                    "Our model is straightforward — you invest in a specific product batch, we handle sourcing, importing, logistics, and sales. Once the product is sold, you receive your original capital back plus your agreed profit share. No hidden fees, no vague promises."
                ],
                'bullets'        => [
                    'Weekly profit payouts',
                    'Full capital return',
                    'Real-time tracking',
                    'Verified opportunities',
                ],
            ],
            'values' => [
                'show_section' => true,
                'label'        => 'What We Stand For',
                'title'        => 'Our Core Values',
                'subtitle'     => 'Every decision we make is guided by these principles.',
                'items'        => [
                    [
                        'icon'  => 'fas fa-shield-halved',
                        'color' => '#34D399',
                        'title' => 'Transparency',
                        'text'  => 'Every investment is publicly visible. You can see exactly how much has been raised, who invested, and what the expected returns are before you commit.',
                    ],
                    [
                        'icon'  => 'fas fa-lock',
                        'color' => '#60A5FA',
                        'title' => 'Security',
                        'text'  => 'Your investment is backed by real physical products. We only import products that already have confirmed buyers or strong market demand.',
                    ],
                    [
                        'icon'  => 'fas fa-handshake',
                        'color' => '#F59E0B',
                        'title' => 'Integrity',
                        'text'  => 'We honor every commitment. If a product underperforms, we communicate proactively and work to protect investor capital.',
                    ],
                    [
                        'icon'  => 'fas fa-rocket',
                        'color' => '#C084FC',
                        'title' => 'Growth',
                        'text'  => 'We are constantly expanding our product catalog and investment opportunities, creating more ways for investors to earn consistent returns.',
                    ],
                ],
            ],
            'process' => [
                'show_section' => true,
                'label'        => 'Our Process',
                'title'        => 'How We Operate',
                'subtitle'     => 'A transparent, end-to-end process that protects your investment at every stage.',
                'steps'        => [
                    ['num' => '01', 'title' => 'Product Research', 'text' => 'Our sourcing team identifies high-demand products with proven sales velocity in the local market.'],
                    ['num' => '02', 'title' => 'Investment Round', 'text' => 'The opportunity is posted on the platform. Investors contribute their preferred amounts within the target funding period.'],
                    ['num' => '03', 'title' => 'Import & Logistics', 'text' => 'Once funded, we place the order, handle all import duties, customs clearance, and warehousing.'],
                    ['num' => '04', 'title' => 'Sales & Distribution', 'text' => 'Products are sold through our established distribution channels. Sales tracking is updated in real time.'],
                    ['num' => '05', 'title' => 'Profit Distribution', 'text' => 'Profits are calculated per-piece and distributed to investors weekly as sales proceed.'],
                    ['num' => '06', 'title' => 'Capital Return', 'text' => 'Once all units are sold, your original investment capital is returned in full along with the final profit settlement.'],
                ],
            ],
            'cta' => [
                'show_section' => true,
                'title'        => 'Ready to Invest With Us?',
                'subtitle'     => 'Join thousands of smart investors earning reliable weekly returns through our transparent import investment model.',
                'btn1_text'    => 'Create Free Account',
                'btn1_link'    => '/register',
                'btn2_text'    => 'View Opportunities',
                'btn2_link'    => '/opportunities',
            ],
        ];
    }

    /**
     * Default Contact Page content structure.
     */
    public static function defaultContactContent(): array
    {
        return [
            'hero' => [
                'show_badge'     => true,
                'badge_text'     => 'Get In Touch',
                'badge_icon'     => 'fas fa-headset',
                'title'          => 'Contact Us',
                'highlight_text' => 'Us',
                'description'    => 'Have a question, need help with your investment, or just want to say hello? Our team is here to help you every step of the way.',
            ],
            'cards' => [
                'show_section'     => true,
                'email_label'      => 'Email Us',
                'email_sub'        => 'We reply within 24 hours',
                'phone_label'      => 'Call Us',
                'phone_sub'        => 'Sat–Thu, 9:00 AM – 7:00 PM',
                'address_label'    => 'Visit Us',
                'address_sub'      => 'By appointment only',
            ],
            'form' => [
                'title'    => 'Send Us a Message',
                'subtitle' => 'Fill out the form and our team will get back to you within 24 hours.',
                'subjects' => [
                    'Investment Inquiry',
                    'Withdrawal Help',
                    'Account Support',
                    'Partnership',
                    'Other',
                ],
            ],
            'hours' => [
                'show_section' => true,
                'title'        => 'Business Hours',
                'items'        => [
                    ['day' => 'Saturday – Thursday', 'time' => '9:00 AM – 7:00 PM'],
                    ['day' => 'Friday', 'time' => 'Closed'],
                ],
            ],
            'whatsapp' => [
                'show_section' => true,
                'title'        => 'Chat on WhatsApp',
                'description'  => 'For quick answers, reach us directly on WhatsApp. Our team responds promptly.',
                'btn_text'     => 'Start Chat',
            ],
            'faq' => [
                'show_section' => true,
                'label'        => 'Common Questions',
                'title'        => 'Frequently Asked Questions',
                'subtitle'     => "Can't find your answer below? Feel free to send us a message above.",
                'items'        => [
                    ['q' => 'How do I start investing?', 'a' => 'Simply create a free account, browse the active investment opportunities, choose one that fits your budget, and submit your investment amount.'],
                    ['q' => 'What is the minimum investment amount?', 'a' => 'Each opportunity has its own minimum investment amount, which is clearly shown on the opportunity card.'],
                    ['q' => 'When do I receive my profits?', 'a' => 'Profits are distributed on a weekly basis as products are sold. You can track your expected payouts from your investor dashboard.'],
                    ['q' => 'Is my investment capital safe?', 'a' => 'Your capital is backed by real physical products. We only post opportunities for products with confirmed market demand.'],
                    ['q' => 'How do I withdraw my earnings?', 'a' => 'Once your profits or returned capital are credited to your account, you can submit a withdrawal request from your dashboard.'],
                ],
            ],
        ];
    }

    /**
     * Default Privacy Policy content structure.
     */
    public static function defaultPrivacyContent(): array
    {
        return [
            'hero' => [
                'show_badge'     => true,
                'badge_text'     => 'Legal Document',
                'badge_icon'     => 'fas fa-user-shield',
                'title'          => 'Privacy Policy',
                'highlight_text' => 'Policy',
                'description'    => 'We take your privacy seriously. This policy explains how we collect, use, and protect your personal information.',
            ],
            'intro' => 'Welcome to {company}. Your privacy is critically important to us. This Privacy Policy explains what personal information we collect, how we use it, and the choices you have. By using our platform, you agree to the terms described in this policy.',
            'sections' => [
                [
                    'num'   => '01',
                    'id'    => 'pp-info',
                    'title' => 'Information We Collect',
                    'body'  => "We collect information you provide directly to us when you:\n• Create an account (name, email address, phone number)\n• Submit investment bids or payment information\n• Contact our support team\n• Fill out any forms on our platform\n\nWe also automatically collect technical information such as IP address, browser type, pages visited, and device information to optimize platform performance.",
                ],
                [
                    'num'   => '02',
                    'id'    => 'pp-use',
                    'title' => 'How We Use Your Data',
                    'body'  => "We use the information we collect to:\n• Provide, maintain, and improve our investment platform\n• Process your investments and distribute profits\n• Send transaction confirmations and account notifications\n• Respond to your questions and support requests\n• Comply with legal obligations and prevent fraudulent activity",
                ],
                [
                    'num'   => '03',
                    'id'    => 'pp-sharing',
                    'title' => 'Data Sharing',
                    'body'  => "We do not sell, rent, or trade your personal information with third parties for marketing purposes. We share data only:\n• With your explicit consent\n• To comply with legal requirements or court orders\n• With verified payment and infrastructure partners under strict confidentiality agreements",
                ],
                [
                    'num'   => '04',
                    'id'    => 'pp-security',
                    'title' => 'Data Security',
                    'body'  => 'We implement industry-standard encryption, SSL transmission, hashed passwords, and strict role-based access controls to safeguard your personal and financial information.',
                ],
                [
                    'num'   => '05',
                    'id'    => 'pp-cookies',
                    'title' => 'Cookies & Session Data',
                    'body'  => 'We use essential cookies solely for session maintenance, security, and authentication. We do not use third-party tracking cookies for targeted advertising.',
                ],
                [
                    'num'   => '06',
                    'id'    => 'pp-rights',
                    'title' => 'Your Rights',
                    'body'  => 'You have the right to access, review, update, or request deletion of your personal account data at any time by contacting our support team.',
                ],
                [
                    'num'   => '07',
                    'id'    => 'pp-retention',
                    'title' => 'Data Retention',
                    'body'  => 'We retain your personal data for as long as your account remains active or as required by financial record-keeping laws and regulatory compliance.',
                ],
                [
                    'num'   => '08',
                    'id'    => 'pp-changes',
                    'title' => 'Policy Changes',
                    'body'  => 'We may update this Privacy Policy from time to time. Any significant updates will be communicated directly via email or posted prominently on our platform.',
                ],
                [
                    'num'   => '09',
                    'id'    => 'pp-contact',
                    'title' => 'Contact Us',
                    'body'  => "If you have questions about this Privacy Policy, reach out to us at {email} or call {phone}.",
                ],
            ],
        ];
    }

    /**
     * Default Terms & Conditions content structure.
     */
    public static function defaultTermsContent(): array
    {
        return [
            'hero' => [
                'show_badge'     => true,
                'badge_text'     => 'Legal Document',
                'badge_icon'     => 'fas fa-scale-balanced',
                'title'          => 'Terms & Conditions',
                'highlight_text' => 'Conditions',
                'description'    => 'Please read these terms carefully before using our investment platform. By accessing or using our services, you agree to be bound by these terms.',
            ],
            'intro' => 'These Terms and Conditions ("Terms") govern your access to and use of the {company} investment platform ("Platform"). By registering, accessing, or using the Platform, you confirm that you have read, understood, and agree to be bound by these Terms.',
            'sections' => [
                [
                    'num'   => '01',
                    'id'    => 'tc-acceptance',
                    'title' => 'Acceptance of Terms',
                    'body'  => 'By creating an account or using any part of our Platform, you represent and warrant that you have the legal capacity to enter into a binding agreement and that you agree to comply with all applicable terms and regulations.',
                ],
                [
                    'num'   => '02',
                    'id'    => 'tc-eligibility',
                    'title' => 'Eligibility',
                    'body'  => "To participate in investments on our Platform, you must:\n• Be at least 18 years of age\n• Have the legal authority to enter into financial contracts\n• Provide accurate, verified identification and contact information",
                ],
                [
                    'num'   => '03',
                    'id'    => 'tc-accounts',
                    'title' => 'User Accounts & Security',
                    'body'  => 'You are responsible for keeping your credentials confidential. Each user may hold only one registered account. Any fraudulent activity or account sharing will result in immediate suspension.',
                ],
                [
                    'num'   => '04',
                    'id'    => 'tc-investments',
                    'title' => 'Investment Terms & Approval',
                    'body'  => "All investments posted on the Platform represent real physical product batches.\n• Bids are subject to admin review and approval\n• Approved investments must be funded within the designated payment window\n• Once funded and confirmed, active investments cannot be unilaterally cancelled",
                ],
                [
                    'num'   => '05',
                    'id'    => 'tc-profits',
                    'title' => 'Profits & Distribution',
                    'body'  => 'Profit payouts are distributed periodically based on realized product sales. Full investment principal is returned upon completion of the product sales cycle.',
                ],
                [
                    'num'   => '06',
                    'id'    => 'tc-risks',
                    'title' => 'Risk Disclosure',
                    'body'  => 'Every commercial import venture carries operational variables including customs clearance, shipping timelines, and retail sales velocity. We mitigate risks through stringent pre-market validation.',
                ],
                [
                    'num'   => '07',
                    'id'    => 'tc-prohibited',
                    'title' => 'Prohibited Conduct',
                    'body'  => 'Users agree not to submit deceptive payment receipts, tamper with the platform code, or engage in any unlawful activity while using the services.',
                ],
                [
                    'num'   => '08',
                    'id'    => 'tc-liability',
                    'title' => 'Limitation of Liability',
                    'body'  => '{company} operates in full good faith to execute import, sales, and profit distribution. Maximum platform liability is strictly limited to the verified capital contributed by the user.',
                ],
                [
                    'num'   => '09',
                    'id'    => 'tc-governing',
                    'title' => 'Governing Law',
                    'body'  => "These Terms shall be governed by and construed under the applicable laws and judicial jurisdiction of the country of operation.",
                ],
                [
                    'num'   => '10',
                    'id'    => 'tc-contact',
                    'title' => 'Contact Us',
                    'body'  => "For questions regarding these Terms, contact us at {email} or {phone}.",
                ],
            ],
        ];
    }

    /**
     * Default Active Opportunities content structure.
     */
    public static function defaultOpportunitiesContent(): array
    {
        return [
            'hero' => [
                'show_badge'     => true,
                'badge_text'     => 'Live Opportunities',
                'title'          => 'Active Opportunities',
                'highlight_text' => 'Opportunities',
                'description'    => 'Browse real import investment opportunities. Each listing shows full investment details, funding progress, and expected weekly returns.',
            ],
            'guest_banner' => [
                'show'       => true,
                'title'      => 'Sign In to View Full Details & Invest',
                'text'       => 'Create a free account or log in to see full investment specifications, funding progress, and to place your investment bid.',
                'btn1_text'  => 'Create Free Account',
                'btn2_text'  => 'Sign In',
            ],
            'bottom_features' => [
                'show_section' => true,
                'items' => [
                    [
                        'icon'  => 'fas fa-calendar-alt',
                        'class' => 'bg-primary-subtle text-primary',
                        'label' => '{time_label}',
                        'value' => '{expected_import_days} Days',
                    ],
                    [
                        'icon'  => 'fas fa-wallet',
                        'class' => 'bg-success-subtle text-success',
                        'label' => 'Profit Payment',
                        'value' => '{msg_profit_payment}',
                    ],
                    [
                        'icon'  => 'fas fa-arrows-rotate',
                        'class' => 'bg-info-subtle text-info',
                        'label' => 'Return Type',
                        'value' => '{return_type}',
                    ],
                    [
                        'icon'  => 'fas fa-user-shield',
                        'class' => 'bg-warning-subtle text-warning',
                        'label' => 'Investment Type',
                        'value' => '{type}',
                    ],
                ],
            ],
            'empty_state' => [
                'title'    => 'No Active Opportunities Right Now',
                'text'     => "We're sourcing the next batch of investment products. Check back soon or register to be notified.",
                'btn_text' => 'Get Notified',
            ],
        ];
    }
}

