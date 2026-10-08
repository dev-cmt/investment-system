@extends('frontend.layouts.master')

@push('styles')
<style>
  /* ── Active Opportunities Page Styles ── */
  .page-hero {
    background: linear-gradient(135deg, #f0fdf8 0%, #e8f5ee 50%, #d1fae5 100%);
    padding: 70px 0 50px;
    position: relative;
    overflow: hidden;
  }
  .page-hero::before {
    content: '';
    position: absolute;
    top: -120px;
    right: -120px;
    width: 500px;
    height: 500px;
    background: radial-gradient(circle, rgba(26,158,79,0.08) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
  }
  .page-hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--green-light);
    color: var(--green-primary);
    font-size: 0.78rem;
    font-weight: 700;
    padding: 6px 16px;
    border-radius: 30px;
    border: 1px solid rgba(26,158,79,0.25);
    margin-bottom: 14px;
    letter-spacing: 0.5px;
    text-transform: uppercase;
  }
  .page-hero-badge .pulse-dot {
    width: 8px;
    height: 8px;
    background: var(--green-primary);
    border-radius: 50%;
    animation: blink 1.2s infinite;
    flex-shrink: 0;
  }
  .page-hero-title {
    font-size: 2.6rem;
    font-weight: 800;
    color: var(--text-dark);
    letter-spacing: -0.8px;
    line-height: 1.15;
    margin-bottom: 14px;
  }
  .page-hero-title span {
    color: var(--green-primary);
  }
  .page-hero-desc {
    font-size: 1.02rem;
    color: var(--text-muted);
    line-height: 1.7;
    max-width: 620px;
    margin-bottom: 0;
  }
  .page-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.82rem;
    color: var(--text-muted);
    margin-top: 18px;
  }
  .page-breadcrumb a {
    color: var(--green-primary);
    font-weight: 600;
    text-decoration: none;
  }
  .page-breadcrumb i {
    font-size: 0.65rem;
  }

  /* Filter bar */
  .filter-bar {
    background: #fff;
    border-bottom: 1px solid var(--border-color);
    padding: 14px 0;
    position: sticky;
    top: 66px;
    z-index: 99;
    box-shadow: 0 2px 10px rgba(0,0,0,0.02);
  }
  .filter-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 18px;
    border-radius: 30px;
    font-size: 0.83rem;
    font-weight: 600;
    border: 1px solid var(--border-color);
    background: #fff;
    color: var(--text-muted);
    cursor: pointer;
    transition: var(--transition);
  }
  .filter-btn.active, .filter-btn:hover {
    background: var(--green-primary);
    color: #fff;
    border-color: var(--green-primary);
    box-shadow: 0 2px 8px rgba(26,158,79,0.25);
  }

  /* Opportunities Grid Section */
  .opportunities-page-section {
    padding: 50px 0 70px;
    background: #f8fafc;
  }

  /* Opportunity card styles identical to index.blade.php */
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

  .card-body-inner {
    display: grid;
    grid-template-columns: 130px 1fr;
    gap: 16px;
    margin-bottom: 14px;
  }

  .product-title {
    font-size: 1rem;
    font-weight: 700;
    color: var(--text-dark);
    line-height: 1.35;
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

  .funding-progress {
    margin-top: auto;
    padding-top: 10px;
    border-top: 1px solid var(--border-color);
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

  /* Login Prompt Banner */
  .login-prompt-banner {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    border-radius: 18px;
    padding: 40px 30px;
    text-align: center;
    color: #fff;
    margin-bottom: 40px;
    border: 1px solid rgba(255,255,255,0.08);
  }
  .login-prompt-banner h2 {
    font-size: 1.6rem;
    font-weight: 800;
    margin-bottom: 10px;
  }
  .login-prompt-banner p {
    font-size: 0.92rem;
    color: #94a3b8;
    max-width: 520px;
    margin: 0 auto 20px;
    line-height: 1.65;
  }

  /* Empty state */
  .empty-state {
    text-align: center;
    padding: 70px 20px;
    background: #fff;
    border-radius: 16px;
    border: 1px dashed var(--border-color);
  }
  .empty-state-icon {
    font-size: 3.5rem;
    color: var(--green-primary);
    margin-bottom: 16px;
    opacity: 0.8;
  }
  .empty-state h3 {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 8px;
  }
  .empty-state p {
    font-size: 0.88rem;
    color: var(--text-muted);
    max-width: 460px;
    margin: 0 auto;
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

  /* Why Invest Cards */
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

  @media (max-width: 768px) {
    .page-hero-title { font-size: 2rem; }
    .filter-bar { top: 58px; }
    .card-body-inner { grid-template-columns: 1fr; gap: 12px; }
    .opp-img-wrap { height: 160px; }
    .opp-img-wrap img { height: 160px; }
  }
</style>
@endpush

@section('content')

  @php
    $hero = $content['hero'] ?? [];
    $guestBanner = $content['guest_banner'] ?? [];
    $emptyState = $content['empty_state'] ?? [];
    $types = !empty($availableTypes) ? $availableTypes : ['Import', 'Local', 'Manufacture'];

    // Load shared home content for complete page design flow
    $homePage = \App\Models\Page::getPage('home');
    $homeContent = $homePage->content ?? \App\Models\Page::defaultHomeContent();
  @endphp

  {{-- ── 1. Page Hero Banner ── --}}
  <section class="page-hero">
    <div class="container">
      @if(!empty($hero['show_badge'] ?? true))
        <div class="page-hero-badge" data-aos="fade-down">
          <span class="pulse-dot"></span> {{ $hero['badge_text'] ?? 'Live Opportunities' }}
        </div>
      @endif

      <h1 class="page-hero-title" data-aos="fade-up" data-aos-delay="50">
        Active <span>Opportunities</span>
      </h1>

      <p class="page-hero-desc" data-aos="fade-up" data-aos-delay="100">
        {{ $hero['description'] ?? 'Browse verified product investment batches. Monitor live funding progress, member counts, and expected weekly returns.' }}
      </p>

      <div class="d-flex align-items-center gap-3 mt-3 flex-wrap" data-aos="fade-up" data-aos-delay="150">
        <div style="background:var(--green-light);color:var(--green-primary);border-radius:10px;padding:8px 16px;display:inline-flex;align-items:center;gap:8px;font-weight:700;font-size:0.86rem;">
          <i class="fas fa-fire"></i>
          <span>{{ $posts->count() }} Active {{ Str::plural('Opportunity', $posts->count()) }}</span>
        </div>
        <div style="font-size:0.82rem;color:var(--text-muted);display:flex;align-items:center;gap:6px;">
          <i class="fas fa-clock text-success"></i> Updated in real time
        </div>
      </div>

      <nav class="page-breadcrumb" aria-label="Breadcrumb" data-aos="fade-up" data-aos-delay="200">
        <a href="{{ route('home') }}">Home</a>
        <i class="fas fa-chevron-right"></i>
        <span>Active Opportunities</span>
      </nav>
    </div>
  </section>

  {{-- ── 2. Filter Bar ── --}}
  <div class="filter-bar">
    <div class="container">
      <div class="d-flex align-items-center gap-2 flex-wrap">
        <button class="filter-btn active" data-filter="all" id="filterAll">
          <i class="fas fa-th-large"></i> All
        </button>
        @foreach($types as $t)
          <button class="filter-btn" data-filter="{{ $t }}" id="filter_{{ Str::slug($t) }}">
            <i class="fas {{ $t === 'Local' ? 'fa-location-dot' : ($t === 'Manufacture' ? 'fa-industry' : 'fa-ship') }}"></i> {{ $t }}
          </button>
        @endforeach
      </div>
    </div>
  </div>

  {{-- ── 3. Opportunities Grid (Flow matching index.blade.php) ── --}}
  <section class="opportunities-page-section" id="opportunitiesSection">
    <div class="container">

      {{-- Guest Banner Prompt --}}
      @guest
        @if($guestBanner['show'] ?? true)
          <div class="login-prompt-banner" data-aos="fade-up">
            <i class="fas fa-lock-open" style="font-size:2.2rem;color:#34d399;margin-bottom:14px;display:block;"></i>
            <h2>{{ $guestBanner['title'] ?? 'Sign In to View Full Details & Invest' }}</h2>
            <p>{{ $guestBanner['text'] ?? 'Create a free account or log in to see full investment specifications, funding progress, and to place your investment bid.' }}</p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
              <a href="{{ route('register') }}" class="btn btn-success rounded-pill px-4 py-2.5 fw-bold shadow">
                <i class="fas fa-user-plus me-1.5"></i>{{ $guestBanner['btn1_text'] ?? 'Create Free Account' }}
              </a>
              <a href="{{ route('login') }}" class="btn btn-outline-light rounded-pill px-4 py-2.5 fw-bold">
                <i class="fas fa-sign-in-alt me-1.5"></i>{{ $guestBanner['btn2_text'] ?? 'Sign In' }}
              </a>
            </div>
          </div>
        @endif
      @endguest

      {{-- Cards Grid --}}
      <div class="row g-4" id="opportunitiesGrid">
        @forelse($posts as $index => $post)
          <div class="col-lg-4 col-md-6 d-flex opp-item"
               data-type="{{ $post->type ?? 'Import' }}"
               data-aos="fade-up" data-aos-delay="{{ $index * 70 }}">
            <div class="opp-card w-100">

              {{-- Meta row --}}
              <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-center gap-1.5">
                  <span class="badge-active">
                    <i class="fa-solid fa-circle-dot fa-beat" style="font-size:0.6rem;"></i> Active
                  </span>
                  <span class="badge {{ $post->type === 'Local' ? 'bg-warning-subtle text-dark border border-warning' : ($post->type === 'Manufacture' ? 'bg-info-subtle text-dark border border-info' : 'bg-success-subtle text-success border border-success-subtle') }} rounded-pill px-2.5 py-1 extra-small fw-bold">
                    <i class="fas {{ $post->type === 'Local' ? 'fa-location-dot' : ($post->type === 'Manufacture' ? 'fa-industry' : 'fa-ship') }} me-1"></i>{{ $post->type ?? 'Import' }}
                  </span>
                </div>
                <div class="d-flex align-items-center gap-2">
                  {{-- Clickable member count badge --}}
                  <a href="{{ route('investments.index', ['investment_post_id' => $post->id]) }}"
                     class="text-decoration-none" title="View investors for this opportunity">
                    <span style="font-size:0.72rem;font-weight:700;background:#e8f5ee;color:#1a9e4f;border:1px solid #a7f3d0;border-radius:20px;padding:2px 10px;white-space:nowrap;">
                      <i class="fas fa-users" style="font-size:0.65rem;"></i>
                      {{ $post->member_count ?? 0 }}
                      Member{{ ($post->member_count ?? 0) != 1 ? 's' : '' }}
                    </span>
                  </a>
                  <span class="posted-date">{{ $post->created_at->format('d M Y') }}</span>
                </div>
              </div>

              {{-- Image + Spec grid (.card-body-inner) --}}
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

                {{-- Specs list --}}
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
                      <span class="spec-label"><i class="fas fa-money-bill-wave"></i> Total Investment</span>
                      <span class="spec-val">&#2547;{{ number_format($post->target_amount) }}</span>
                    </li>
                    <li>
                      <span class="spec-label"><i class="fas fa-calendar-days"></i> {{ $post->time_label }}</span>
                      <span class="spec-val">{{ $post->expected_import_days }} Days</span>
                    </li>
                    <li>
                      <span class="spec-label"><i class="fas fa-chart-line"></i> Profit per Piece</span>
                      <span class="spec-val green">&#2547;{{ number_format($post->profit_per_unit) }}</span>
                    </li>
                    <li>
                      <span class="spec-label"><i class="fas fa-wallet"></i> Profit Payment</span>
                      <span class="spec-val">{{ $post->msg_profit_payment ?? 'Weekly' }}</span>
                    </li>
                    <li>
                      <span class="spec-label"><i class="fas fa-user-shield"></i> Members Invested</span>
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
                  <span>Invested: <strong>&#2547;{{ number_format($post->current_invested_amount) }}</strong></span>
                  <span>Remaining: <strong>&#2547;{{ number_format($post->remaining_amount) }}</strong></span>
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
          <div class="col-12">
            <div class="empty-state">
              <div class="empty-state-icon"><i class="fas fa-box-open"></i></div>
              <h3>{{ $emptyState['title'] ?? 'No Active Opportunities Right Now' }}</h3>
              <p>{{ $emptyState['text'] ?? "We're sourcing the next batch of investment products. Check back soon or register to be notified." }}</p>
              @guest
                <a href="{{ route('register') }}" class="btn btn-success rounded-pill px-5 py-2.5 fw-bold mt-3 shadow-sm">
                  <i class="fas fa-bell me-1.5"></i>{{ $emptyState['btn_text'] ?? 'Get Notified' }}
                </a>
              @endguest
            </div>
          </div>
        @endforelse
      </div>

    </div>
  </section>

  {{-- ── 4. Features Strip (From home flow) ── --}}
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

  {{-- ── 5. How It Works Section (From home flow) ── --}}
  @if(!empty($homeContent['how_it_works']['show_section']))
    <section class="py-5 bg-light" id="howItWorks">
      <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
          <div class="section-label mb-1">{{ $homeContent['how_it_works']['section_label'] ?? 'Step by Step' }}</div>
          <h2 class="section-title">{{ $homeContent['how_it_works']['title'] ?? 'How It Works' }}</h2>
          <p class="text-muted small mt-1 mb-0">{{ $homeContent['how_it_works']['subtitle'] ?? 'A transparent process from investment batch to weekly return' }}</p>
        </div>

        <div class="row g-4 justify-content-center">
          @php
            $steps = $homeContent['how_it_works']['steps'] ?? [];
            $stepCol = count($steps) <= 4 ? 'col-md-3 col-sm-6' : 'col-6 col-md-4 col-lg-2';
          @endphp
          @foreach ($steps as $i => $step)
            <div class="{{ $stepCol }} text-center step-card" data-aos="fade-up" data-aos-delay="{{ $i * 70 }}">
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

  {{-- ── 6. Why Invest With Us Section (From home flow) ── --}}
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

  {{-- ── 7. Call To Action Banner ── --}}
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

@push('scripts')
<script>
  // Filter buttons interaction
  document.querySelectorAll('.filter-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
      document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
      this.classList.add('active');
      var filter = this.dataset.filter;
      document.querySelectorAll('.opp-item').forEach(function(item) {
        if (filter === 'all' || item.dataset.type === filter) {
          item.style.display = '';
        } else {
          item.style.display = 'none';
        }
      });
      // Refresh AOS layout positions
      if (typeof AOS !== 'undefined') {
        AOS.refresh();
      }
    });
  });
</script>
@endpush
