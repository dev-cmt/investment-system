@extends('frontend.layouts.master')

@push('styles')
    <style>
        /* ---- Index Page Styles ---- */
        .trust-badge-box {
            display: flex;
            align-items: center;
            gap: 12px;
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 10px 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .trust-badge-icon {
            width: 40px;
            height: 40px;
            border-radius: 9px;
            background: var(--green-light);
            color: var(--green-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .trust-badge-title {
            font-size: 0.88rem;
            font-weight: 700;
            color: var(--text-dark);
        }

        .trust-badge-sub {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        /* Opportunity card tweaks */
        .opp-card {
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 14px;
            padding: 18px;
            transition: var(--transition);
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .opp-card:hover {
            box-shadow: var(--card-shadow-h);
            transform: translateY(-3px);
            border-color: rgba(26, 158, 79, 0.2);
        }

        .opp-img-wrap {
            border-radius: 10px;
            background: #f6f8fa;
            overflow: hidden;
            height: 130px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .opp-img-wrap img {
            width: 100%;
            height: 130px;
            object-fit: cover;
            transition: var(--transition);
        }

        .opp-card:hover .opp-img-wrap img {
            transform: scale(1.05);
        }

        .spec-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .spec-list li {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.79rem;
        }

        .spec-label {
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .spec-label i {
            color: var(--green-primary);
            width: 13px;
        }

        .spec-val {
            font-weight: 600;
            color: var(--text-dark);
        }

        .spec-val.green {
            color: var(--green-primary);
        }

        .funded-pct {
            font-size: 0.81rem;
            font-weight: 700;
            color: var(--green-primary);
            margin-bottom: 5px;
        }

        .invest-amounts {
            display: flex;
            justify-content: space-between;
            font-size: 0.76rem;
            color: var(--text-muted);
            margin-top: 7px;
        }

        .invest-amounts strong {
            color: var(--text-dark);
            font-weight: 600;
        }

        /* How It Works steps */
        .step-icon {
            width: 60px;
            height: 60px;
            border-radius: 14px;
            background: var(--green-light);
            color: var(--green-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            margin: 0 auto 12px;
            transition: var(--transition);
        }

        .step-card:hover .step-icon {
            background: var(--green-primary);
            color: #fff;
            transform: scale(1.08);
        }

        .step-num {
            display: inline-block;
            font-size: 0.72rem;
            font-weight: 700;
            background: var(--green-primary);
            color: #fff;
            padding: 2px 10px;
            border-radius: 20px;
            margin-bottom: 8px;
        }

        .step-title {
            font-size: 0.88rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 5px;
        }

        .step-desc {
            font-size: 0.76rem;
            color: var(--text-muted);
        }

        /* Why Invest */
        .why-card {
            background: #1E293B;
            border-radius: 14px;
            padding: 24px;
            height: 100%;
            transition: var(--transition);
        }

        .why-card:hover {
            background: #243448;
            transform: translateY(-3px);
        }

        .why-icon {
            font-size: 1.6rem;
            margin-bottom: 14px;
        }

        .section-label {
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--green-primary);
        }
    </style>
@endpush

@section('content')
    @php
        $homeContent = $homeContent ?? ($page->content ?? \App\Models\Page::defaultHomeContent());
    @endphp

    {{-- ─────────────── HERO ─────────────── --}}
    <section class="hero-section" id="heroSection">
        <div class="container">
            <div class="row align-items-center g-4">

                {{-- Left --}}
                <div class="col-lg-6">
                    @if(!empty($homeContent['hero']['show_badge']))
                        <div class="hero-live-badge mb-3" data-aos="fade-down">
                            <span class="hero-live-dot"></span> {{ $homeContent['hero']['badge_text'] ?? 'LIVE INVESTMENTS ACTIVE' }}
                        </div>
                    @endif

                    @php
                        $heroTitle = $homeContent['hero']['title'] ?? "Invest in Real Products\nEarn Weekly Profit";
                        $highlight = $homeContent['hero']['highlight_text'] ?? 'Weekly Profit';
                        $formattedTitle = nl2br(e($heroTitle));
                        if (!empty($highlight)) {
                            $formattedTitle = str_ireplace(e($highlight), '<span class="highlight">' . e($highlight) . '</span>', $formattedTitle);
                        }
                    @endphp
                    <h1 class="hero-title" data-aos="fade-up" data-aos-delay="50">
                        {!! $formattedTitle !!}
                    </h1>

                    <p class="hero-desc" data-aos="fade-up" data-aos-delay="100">
                        {!! nl2br(e($homeContent['hero']['description'] ?? "We import high demand products from China.\nYou invest, we handle the rest and you earn\nweekly profit after sales.")) !!}
                    </p>

                    <div class="d-flex align-items-center gap-3 mb-4" data-aos="fade-up" data-aos-delay="150">
                        @auth
                            <a href="{{ $homeContent['hero']['btn1_link'] ?? '#opportunities' }}" class="btn btn-signup rounded-pill px-4 py-2.5 fw-bold shadow-sm">
                                <i class="fas fa-rocket me-1.5"></i> {{ $homeContent['hero']['btn1_auth_text'] ?? ($homeContent['hero']['btn1_text'] ?? 'Explore Opportunities') }}
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="btn btn-signup rounded-pill px-4 py-2.5 fw-bold shadow-sm">
                                <i class="fas fa-user-plus me-1.5"></i> {{ $homeContent['hero']['btn1_guest_text'] ?? 'Sign Up to Invest' }}
                            </a>
                        @endauth
                        <a href="{{ $homeContent['hero']['btn2_link'] ?? '#howItWorks' }}" class="btn btn-outline-success rounded-pill px-4 py-2.5 fw-bold">
                            <i class="fas fa-play-circle me-1.5"></i> {{ $homeContent['hero']['btn2_text'] ?? 'How It Works' }}
                        </a>
                    </div>

                    @if(!empty($homeContent['hero']['trust_badges']))
                        <div class="hero-badges" data-aos="fade-up" data-aos-delay="200">
                            @foreach($homeContent['hero']['trust_badges'] as $tb)
                                <div class="hero-badge-item">
                                    <div class="hero-badge-icon"><i class="{{ $tb['icon'] ?? 'fa-solid fa-shield-heart' }}"></i></div>
                                    <div>
                                        <span class="hero-badge-label">{{ $tb['title'] ?? '' }}</span>
                                        <span class="hero-badge-sub">{{ $tb['subtitle'] ?? '' }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Right image & floating cards / Video --}}
                <div class="col-lg-6" data-aos="zoom-in" data-aos-delay="200">
                    <div class="hero-image-wrapper position-relative overflow-hidden rounded-4 shadow-lg" style="background:#0f172a;">
                        @php
                            $mediaType = $homeContent['hero']['media_type'] ?? 'image';
                            $rawVideoUrl = !empty($homeContent['hero']['video_url']) ? $homeContent['hero']['video_url'] : ($setting->hero_video_url ?? null);
                            $videoUrl = \App\Helpers\VideoHelper::toEmbedUrl($rawVideoUrl);
                            $heroImage = $homeContent['hero']['image'] ?? 'images/hero.jpg';
                        @endphp

                        @if($mediaType === 'video' && !empty($videoUrl))
                            <div class="ratio ratio-16x9 rounded-4 overflow-hidden border border-light border-opacity-10 shadow">
                                <iframe src="{{ $videoUrl }}"
                                        title="Platform Video"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen
                                        style="border:0;"
                                        loading="lazy">
                                </iframe>
                            </div>
                        @else
                            <img src="{{ asset($heroImage) }}" alt="{{ $companyName ?? 'Investment' }}" onerror="this.src='{{ asset('images/hero.jpg') }}'" />
                        @endif

                        {{-- Floating Card 1 (Left) --}}
                        @if(!empty($homeContent['hero']['stat_card_1_label']))
                            <div class="hero-float-card card-left" data-aos="fade-right" data-aos-delay="400">
                                <div class="fc-icon"><i class="{{ $homeContent['hero']['stat_card_1_icon'] ?? 'fas fa-arrow-trend-up' }}"></i></div>
                                <div>
                                    <span class="fc-label">{{ $homeContent['hero']['stat_card_1_label'] ?? 'Total Investors' }}</span>
                                    <span class="fc-value">{{ $homeContent['hero']['stat_card_1_value'] ?? '1,240+' }}</span>
                                </div>
                            </div>
                        @endif

                        {{-- Floating Card 2 (Right) --}}
                        @if(!empty($homeContent['hero']['stat_card_2_label']))
                            <div class="hero-float-card card-right" data-aos="fade-left" data-aos-delay="500">
                                <div class="fc-icon text-success"><i class="{{ $homeContent['hero']['stat_card_2_icon'] ?? 'fa-solid fa-shield-heart' }}"></i></div>
                                <div>
                                    <span class="fc-label">{{ $homeContent['hero']['stat_card_2_label'] ?? 'Secured Investments' }}</span>
                                    <span class="fc-value">{{ $homeContent['hero']['stat_card_2_value'] ?? '100%' }}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ─────────────── OPPORTUNITIES ─────────────── --}}
    @auth
        <section class="opportunities-section" id="opportunities">
            <div class="container">

                {{-- Header --}}
                <div class="section-header" data-aos="fade-up">
                    <h2 class="section-title">{{ $homeContent['opportunities']['title'] ?? 'Active Investment Opportunities' }}</h2>
                    <a href="#howItWorks" class="how-it-works-link">
                        <i class="fas fa-circle-info"></i> {{ $homeContent['opportunities']['how_it_works_text'] ?? 'How It Works' }}
                    </a>
                </div>

                {{-- Cards Grid --}}
                <div class="row g-4">
                    @forelse($posts as $index => $post)
                        <div class="col-lg-4 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="{{ $index * 80 }}">
                            <div class="opp-card w-100">

                                {{-- Meta row --}}
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center gap-1.5">
                                        <span class="badge-active"><i class="fa-solid fa-circle-dot fa-beat"
                                                style="font-size:0.6rem;"></i> Active</span>
                                        <span class="badge {{ $post->type === 'Local' ? 'bg-warning-subtle text-dark border border-warning' : ($post->type === 'Manufacture' ? 'bg-info-subtle text-dark border border-info' : 'bg-success-subtle text-success border border-success-subtle') }} rounded-pill px-2.5 py-1 extra-small fw-bold">
                                            <i class="fas {{ $post->type === 'Local' ? 'fa-location-dot' : ($post->type === 'Manufacture' ? 'fa-industry' : 'fa-ship') }} me-1"></i>{{ $post->type ?? 'Import' }}
                                        </span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        {{-- Clickable member count badge --}}
                                        <a href="{{ route('investments.index', ['investment_post_id' => $post->id]) }}"
                                            class="text-decoration-none" title="View investors for this opportunity">
                                            <span
                                                style="font-size:0.72rem;font-weight:700;background:#e8f5ee;color:#1a9e4f;border:1px solid #a7f3d0;border-radius:20px;padding:2px 10px;white-space:nowrap;">
                                                <i class="fas fa-users" style="font-size:0.65rem;"></i>
                                                {{ $post->member_count ?? 0 }}
                                                Member{{ ($post->member_count ?? 0) != 1 ? 's' : '' }}
                                            </span>
                                        </a>
                                        <span class="posted-date">{{ $post->created_at->format('d M Y') }}</span>
                                    </div>
                                </div>

                                {{-- Image + Spec grid --}}
                                <div class="card-body-inner mb-3">
                                    {{-- Image --}}
                                    <div>
                                        @php
                                            $galleryUrls = $post->gallery_image_urls;
                                        @endphp
                                        <div class="opp-img-wrap mb-2 position-relative">
                                            @foreach ($galleryUrls as $gIdx => $gUrl)
                                                <a href="{{ $gUrl }}"
                                                    class="glightbox {{ $gIdx > 0 ? 'd-none' : 'w-100 h-100 d-flex align-items-center justify-content-center' }}"
                                                    data-gallery="opp-gallery-{{ $post->id }}">
                                                    @if ($gIdx === 0)
                                                        <img src="{{ $gUrl }}" alt="{{ $post->title }}" />
                                                    @endif
                                                </a>
                                            @endforeach
                                            @if (count($galleryUrls) > 1)
                                                <span class="position-absolute bottom-0 end-0 mb-1 me-1 px-2 py-0.5 rounded-pill bg-dark bg-opacity-75 text-white extra-small fw-semibold pe-none"
                                                    style="font-size: 0.65rem; backdrop-filter: blur(3px); z-index: 2;">
                                                    <i class="fas fa-images me-1"></i>{{ count($galleryUrls) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Specs --}}
                                    <div>
                                        <h3 class="product-title mb-2">{{ $post->title }}</h3>
                                        <ul class="spec-list">
                                            <li>
                                                <span class="spec-label"><i class="fas fa-boxes-stacked"></i> Quantity</span>
                                                <span class="spec-val">{{ number_format($post->total_quantity) }} pcs</span>
                                            </li>
                                            <li>
                                                <span class="spec-label"><i class="fas fa-tag"></i> Cost per Piece</span>
                                                <span class="spec-val">&#2547;{{ number_format($post->unit_cost) }}</span>
                                            </li>
                                            <li>
                                                <span class="spec-label"><i class="fas fa-money-bill-wave"></i> Total
                                                    Investment</span>
                                                <span class="spec-val">&#2547;{{ number_format($post->target_amount) }}</span>
                                            </li>
                                            <li>
                                                <span class="spec-label"><i class="fas fa-calendar-days"></i> {{ $post->time_label }}</span>
                                                <span class="spec-val">{{ $post->expected_import_days }} Days</span>
                                            </li>
                                            <li>
                                                <span class="spec-label"><i class="fas fa-chart-line"></i> Profit per
                                                    Piece</span>
                                                <span
                                                    class="spec-val green">&#2547;{{ number_format($post->profit_per_unit) }}</span>
                                            </li>
                                            <li>
                                                <span class="spec-label"><i class="fas fa-wallet"></i> Profit Payment</span>
                                                <span class="spec-val">{{ $post->msg_profit_payment ?? 'Weekly' }}</span>
                                            </li>
                                            <li>
                                                <span class="spec-label"><i class="fas fa-user-shield"></i> Members
                                                    Invested</span>
                                                <a href="{{ route('investments.index', ['investment_post_id' => $post->id]) }}"
                                                    class="spec-val text-decoration-none"
                                                    style="color: #1a9e4f;font-weight:700;">
                                                    {{ number_format($post->member_count ?? 0) }}
                                                    Person{{ ($post->member_count ?? 0) != 1 ? 's' : '' }}
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                {{-- Progress --}}
                                <div class="funding-progress">
                                    <div class="funded-pct">{{ $post->funded_percentage }}% Funded</div>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar"
                                            style="width: {{ $post->funded_percentage }}%"
                                            aria-valuenow="{{ $post->funded_percentage }}" aria-valuemin="0"
                                            aria-valuemax="100">
                                        </div>
                                    </div>
                                    <div class="invest-amounts">
                                        <span>Invested:
                                            <strong>&#2547;{{ number_format($post->current_invested_amount) }}</strong></span>
                                        <span>Remaining:
                                            <strong>&#2547;{{ number_format($post->remaining_amount) }}</strong></span>
                                    </div>
                                </div>

                                {{-- CTA Button → goes to details page --}}
                                <a href="{{ route('opportunity.show', $post->id) }}"
                                    class="btn-invest d-block text-center mt-3 text-decoration-none"
                                    style="border-radius:8px;">
                                    View Details &amp; Invest
                                </a>

                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <p class="text-muted">{{ $homeContent['opportunities']['empty_message'] ?? 'No active investment opportunities right now. Check back soon!' }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>
    @endauth

    {{-- ─────────────── FEATURES STRIP ─────────────── --}}
    @if(!empty($homeContent['features']['show_section']) && !empty($homeContent['features']['items']))
        <section class="features-strip" id="features">
            <div class="container">
                <div class="row g-0">
                    @php
                        $features = $homeContent['features']['items'];
                        $count = count($features);
                        $colClass = $count >= 4 ? 'col-md-3 col-sm-6' : ($count == 3 ? 'col-md-4 col-sm-6' : 'col-md-6 col-sm-6');
                    @endphp
                    @foreach($features as $feat)
                        <div class="{{ $colClass }}">
                            <div class="feature-item">
                                <div class="feature-icon"><i class="{{ $feat['icon'] ?? 'fa-solid fa-shield-heart' }}"></i></div>
                                <div>
                                    <div class="feature-title">{{ $feat['title'] ?? '' }}</div>
                                    <div class="feature-desc">{{ $feat['desc'] ?? '' }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ─────────────── HOW IT WORKS ─────────────── --}}
    @if(!empty($homeContent['how_it_works']['show_section']))
        <section class="py-5 bg-light" id="howItWorks">
            <div class="container">
                <div class="text-center mb-5" data-aos="fade-up">
                    <div class="section-label mb-1">{{ $homeContent['how_it_works']['section_label'] ?? 'Step by Step' }}</div>
                    <h2 class="section-title">{{ $homeContent['how_it_works']['title'] ?? 'How It Works' }}</h2>
                    <p class="text-muted small mt-1 mb-0">{{ $homeContent['how_it_works']['subtitle'] ?? 'A transparent 6-step process from product post to profit' }}</p>
                </div>

                <div class="row g-4 justify-content-center">
                    @php
                        $steps = $homeContent['how_it_works']['steps'] ?? [];
                        $stepCol = count($steps) <= 4 ? 'col-md-3 col-sm-6' : 'col-6 col-md-4 col-lg-2';
                    @endphp
                    @foreach ($steps as $i => $step)
                        <div class="{{ $stepCol }} text-center step-card" data-aos="fade-up"
                            data-aos-delay="{{ $i * 70 }}">
                            <div class="step-icon"><i class="fas {{ $step['icon'] ?? 'fa-check' }}"></i></div>
                            <div class="step-num">{{ $step['num'] ?? sprintf('%02d', $i + 1) }}</div>
                            <div class="step-title">{{ $step['title'] ?? '' }}</div>
                            <p class="step-desc mb-0">{{ $step['desc'] ?? '' }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ─────────────── WHY INVEST WITH US ─────────────── --}}
    @if(!empty($homeContent['why_invest']['show_section']))
        <section class="py-5" style="background:#0F172A;" id="whyUs">
            <div class="container">
                <div class="text-center mb-5" data-aos="fade-up">
                    <h2 class="fw-bold text-white mb-1">{{ $homeContent['why_invest']['title'] ?? 'Why Invest With Us?' }}</h2>
                    <p class="text-secondary small mb-0">{{ $homeContent['why_invest']['subtitle'] ?? 'Built for security, transparency, and consistent weekly returns' }}</p>
                </div>
                <div class="row g-4 justify-content-center">
                    @php
                        $whyCards = $homeContent['why_invest']['cards'] ?? [];
                        $whyCol = count($whyCards) <= 3 ? 'col-md-4 col-sm-6' : 'col-md-3 col-sm-6';
                    @endphp
                    @foreach ($whyCards as $i => $w)
                        <div class="{{ $whyCol }}" data-aos="fade-up" data-aos-delay="{{ $i * 80 }}">
                            <div class="why-card">
                                <div class="why-icon" style="color:{{ $w['color'] ?? '#38BDF8' }}">
                                    <i class="fas {{ $w['icon'] ?? 'fa-shield-halved' }}"></i>
                                </div>
                                <h6 class="fw-bold text-white mb-2">{{ $w['title'] ?? '' }}</h6>
                                <p class="text-secondary small mb-0">{{ $w['desc'] ?? '' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ─────────────── CALL TO ACTION BANNER (OPTIONAL) ─────────────── --}}
    @if(!empty($homeContent['cta']['show_section']))
        <section class="py-5 text-white text-center position-relative overflow-hidden" id="ctaSection" style="background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);">
            <div class="container py-4 position-relative" style="z-index:2;" data-aos="zoom-in">
                <h2 class="fw-bold text-white mb-3">{{ $homeContent['cta']['title'] ?? 'Ready to Start Your Investment Journey?' }}</h2>
                <p class="text-white-50 mb-4 mx-auto" style="max-width: 650px;">{{ $homeContent['cta']['subtitle'] ?? 'Join our verified investors community and earn reliable weekly returns.' }}</p>
                <a href="{{ $homeContent['cta']['btn_link'] ?? route('register') }}" class="btn btn-light rounded-pill px-4 py-2.5 fw-bold text-success shadow-lg">
                    <i class="fas fa-rocket me-1.5"></i> {{ $homeContent['cta']['btn_text'] ?? 'Create Free Account' }}
                </a>
            </div>
        </section>
    @endif
@endsection
