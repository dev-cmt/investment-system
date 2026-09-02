@extends('frontend.layouts.master')

@push('styles')
<style>
  /* ---- Index Page Styles ---- */
  .trust-badge-box {
    display: flex; align-items: center; gap: 12px;
    background: #fff; border: 1px solid var(--border-color);
    border-radius: 10px; padding: 10px 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
  }
  .trust-badge-icon {
    width: 40px; height: 40px; border-radius: 9px;
    background: var(--green-light); color: var(--green-primary);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; flex-shrink: 0;
  }
  .trust-badge-title { font-size: 0.88rem; font-weight: 700; color: var(--text-dark); }
  .trust-badge-sub   { font-size: 0.75rem; color: var(--text-muted); }

  /* Opportunity card tweaks */
  .opp-card {
    background: #fff; border: 1px solid var(--border-color);
    border-radius: 14px; padding: 18px;
    transition: var(--transition); height: 100%;
    display: flex; flex-direction: column; justify-content: space-between;
  }
  .opp-card:hover { box-shadow: var(--card-shadow-h); transform: translateY(-3px); border-color: rgba(26,158,79,0.2); }

  .opp-img-wrap {
    border-radius: 10px; background: #f6f8fa;
    overflow: hidden; height: 130px;
    display: flex; align-items: center; justify-content: center;
  }
  .opp-img-wrap img { width: 100%; height: 130px; object-fit: cover; transition: var(--transition); }
  .opp-card:hover .opp-img-wrap img { transform: scale(1.05); }

  .spec-list { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 5px; }
  .spec-list li { display: flex; align-items: center; justify-content: space-between; font-size: 0.79rem; }
  .spec-label { color: var(--text-muted); display: flex; align-items: center; gap: 5px; }
  .spec-label i { color: var(--green-primary); width: 13px; }
  .spec-val { font-weight: 600; color: var(--text-dark); }
  .spec-val.green { color: var(--green-primary); }

  .funded-pct { font-size: 0.81rem; font-weight: 700; color: var(--green-primary); margin-bottom: 5px; }
  .invest-amounts { display: flex; justify-content: space-between; font-size: 0.76rem; color: var(--text-muted); margin-top: 7px; }
  .invest-amounts strong { color: var(--text-dark); font-weight: 600; }

  /* How It Works steps */
  .step-icon {
    width: 60px; height: 60px; border-radius: 14px;
    background: var(--green-light); color: var(--green-primary);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; margin: 0 auto 12px; transition: var(--transition);
  }
  .step-card:hover .step-icon { background: var(--green-primary); color: #fff; transform: scale(1.08); }
  .step-num {
    display: inline-block; font-size: 0.72rem; font-weight: 700;
    background: var(--green-primary); color: #fff;
    padding: 2px 10px; border-radius: 20px; margin-bottom: 8px;
  }
  .step-title { font-size: 0.88rem; font-weight: 700; color: var(--text-dark); margin-bottom: 5px; }
  .step-desc  { font-size: 0.76rem; color: var(--text-muted); }

  /* Why Invest */
  .why-card {
    background: #1E293B; border-radius: 14px; padding: 24px;
    height: 100%; transition: var(--transition);
  }
  .why-card:hover { background: #243448; transform: translateY(-3px); }
  .why-icon { font-size: 1.6rem; margin-bottom: 14px; }

  .section-label {
    font-size: 0.78rem; font-weight: 700; letter-spacing: 1px;
    text-transform: uppercase; color: var(--green-primary);
  }
</style>
@endpush

@section('content')

{{-- ─────────────── HERO ─────────────── --}}
<section class="hero-section" id="heroSection">
  <div class="container">
    <div class="row align-items-center g-4">

      {{-- Left --}}
      <div class="col-lg-6">
        <div class="hero-live-badge mb-3" data-aos="fade-down">
          <span class="hero-live-dot"></span> LIVE INVESTMENTS ACTIVE
        </div>
        <h1 class="hero-title" data-aos="fade-up" data-aos-delay="50">
          Invest in Real Products<br>
          Earn <span class="highlight">Weekly Profit</span>
        </h1>
        <p class="hero-desc" data-aos="fade-up" data-aos-delay="100">
          We import high demand products from China.<br>
          You invest, we handle the rest and you earn<br>
          weekly profit after sales.
        </p>

        <div class="d-flex align-items-center gap-3 mb-4" data-aos="fade-up" data-aos-delay="150">
          <a href="#opportunities" class="btn btn-signup rounded-pill px-4 py-2.5 fw-bold shadow-sm">
            <i class="fas fa-rocket me-1.5"></i> Explore Opportunities
          </a>
          <a href="#howItWorks" class="btn btn-outline-success rounded-pill px-4 py-2.5 fw-bold">
            <i class="fas fa-play-circle me-1.5"></i> How It Works
          </a>
        </div>

        <div class="hero-badges" data-aos="fade-up" data-aos-delay="200">
          <div class="hero-badge-item">
            <div class="hero-badge-icon"><i class="fas fa-shield-halved"></i></div>
            <div>
              <span class="hero-badge-label">100% Secure</span>
              <span class="hero-badge-sub">Your investment is safe</span>
            </div>
          </div>
          <div class="hero-badge-item">
            <div class="hero-badge-icon"><i class="fas fa-clock"></i></div>
            <div>
              <span class="hero-badge-label">Weekly Profit</span>
              <span class="hero-badge-sub">Paid every week</span>
            </div>
          </div>
          <div class="hero-badge-item">
            <div class="hero-badge-icon"><i class="fas fa-truck-fast"></i></div>
            <div>
              <span class="hero-badge-label">Fast Import</span>
              <span class="hero-badge-sub">Products in 25-30 days</span>
            </div>
          </div>
        </div>
      </div>

      {{-- Right image & floating cards --}}
      <div class="col-lg-6" data-aos="zoom-in" data-aos-delay="200">
        <div class="hero-image-wrapper">
          <a href="{{ asset('images/hero.jpg') }}" class="glightbox" data-gallery="hero">
            <img src="{{ asset('images/hero.jpg') }}" alt="Global import and investment" />
          </a>
          <div class="hero-float-card card-left" data-aos="fade-right" data-aos-delay="400">
            <div class="fc-icon"><i class="fas fa-arrow-trend-up"></i></div>
            <div>
              <span class="fc-label">Total Investors</span>
              <span class="fc-value">1,240+</span>
            </div>
          </div>
          <div class="hero-float-card card-right" data-aos="fade-left" data-aos-delay="500">
            <div class="fc-icon text-success"><i class="fas fa-shield-check"></i></div>
            <div>
              <span class="fc-label">Secured Investments</span>
              <span class="fc-value">100%</span>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

{{-- ─────────────── OPPORTUNITIES ─────────────── --}}
<section class="opportunities-section" id="opportunities">
  <div class="container">

    {{-- Header --}}
    <div class="section-header" data-aos="fade-up">
      <h2 class="section-title">Active Investment Opportunities</h2>
      <a href="#howItWorks" class="how-it-works-link">
        <i class="fas fa-circle-info"></i> How It Works
      </a>
    </div>

    {{-- Cards Grid --}}
    <div class="row g-4">
      @forelse($posts as $index => $post)
        <div class="col-lg-4 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="{{ $index * 80 }}">
          <div class="opp-card w-100">

            {{-- Meta row --}}
            <div class="d-flex align-items-center justify-content-between mb-3">
              <span class="badge-active"><i class="fa-solid fa-circle-dot fa-beat" style="font-size:0.6rem;"></i> Active</span>
              <span class="posted-date">Posted: {{ $post->created_at->format('d M Y') }}</span>
            </div>

            {{-- Image + Spec grid --}}
            <div class="card-body-inner mb-3">
              {{-- Image --}}
              <div>
                <div class="opp-img-wrap mb-2">
                  <a href="{{ asset($post->image ?? 'images/earbuds.jpg') }}" class="glightbox" data-gallery="opportunities">
                    <img src="{{ asset($post->image ?? 'images/earbuds.jpg') }}" alt="{{ $post->title }}" />
                  </a>
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
                    <span class="spec-label"><i class="fas fa-money-bill-wave"></i> Total Investment</span>
                    <span class="spec-val">&#2547;{{ number_format($post->target_amount) }}</span>
                  </li>
                  <li>
                    <span class="spec-label"><i class="fas fa-calendar-days"></i> Import Time</span>
                    <span class="spec-val">{{ $post->expected_import_days }} Days</span>
                  </li>
                  <li>
                    <span class="spec-label"><i class="fas fa-chart-line"></i> Profit per Piece</span>
                    <span class="spec-val green">&#2547;{{ number_format($post->profit_per_unit) }}</span>
                  </li>
                  <li>
                    <span class="spec-label"><i class="fas fa-wallet"></i> Profit Payment</span>
                    <span class="spec-val">Weekly</span>
                  </li>
                  <li>
                    <span class="spec-label"><i class="fas fa-user-shield"></i> Investor Needed</span>
                    <span class="spec-val">1 Person</span>
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
                  aria-valuenow="{{ $post->funded_percentage }}" aria-valuemin="0" aria-valuemax="100">
                </div>
              </div>
              <div class="invest-amounts">
                <span>Invested: <strong>&#2547;{{ number_format($post->current_invested_amount) }}</strong></span>
                <span>Remaining: <strong>&#2547;{{ number_format($post->remaining_amount) }}</strong></span>
              </div>
            </div>

            {{-- CTA Button → goes to details page --}}
            <a href="{{ route('opportunity.show', $post->id) }}" class="btn-invest d-block text-center mt-3 text-decoration-none"
               style="border-radius:8px;">
              View Details &amp; Invest
            </a>

          </div>
        </div>
      @empty
        <div class="col-12 text-center py-5">
          <p class="text-muted">No active investment opportunities right now. Check back soon!</p>
        </div>
      @endforelse
    </div>
  </div>
</section>

{{-- ─────────────── FEATURES STRIP ─────────────── --}}
<section class="features-strip" id="features">
  <div class="container">
    <div class="row g-0">
      <div class="col-md-3 col-sm-6">
        <div class="feature-item">
          <div class="feature-icon"><i class="fas fa-shield-check"></i></div>
          <div>
            <div class="feature-title">Safe &amp; Secure</div>
            <div class="feature-desc">Your investment is protected with full transparency.</div>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="feature-item">
          <div class="feature-icon"><i class="fas fa-arrows-rotate"></i></div>
          <div>
            <div class="feature-title">Weekly Profit</div>
            <div class="feature-desc">Profit will be paid every week after sales start.</div>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="feature-item">
          <div class="feature-icon"><i class="fas fa-truck-fast"></i></div>
          <div>
            <div class="feature-title">Fast Delivery</div>
            <div class="feature-desc">Products are imported within 25-30 days.</div>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="feature-item">
          <div class="feature-icon"><i class="fas fa-headset"></i></div>
          <div>
            <div class="feature-title">Support</div>
            <div class="feature-desc">We are here to help you 24/7.</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ─────────────── HOW IT WORKS ─────────────── --}}
<section class="py-5 bg-light" id="howItWorks">
  <div class="container">
    <div class="text-center mb-5" data-aos="fade-up">
      <div class="section-label mb-1">Step by Step</div>
      <h2 class="section-title">How It Works</h2>
      <p class="text-muted small mt-1 mb-0">A transparent 6-step process from product post to profit</p>
    </div>

    <div class="row g-4">
      @foreach([
        ['icon'=>'fa-boxes-packing',       'num'=>'01','title'=>'Product Posted',    'desc'=>'We post high demand product investment opportunities with full details.'],
        ['icon'=>'fa-hand-holding-dollar', 'num'=>'02','title'=>'Investor Invests',   'desc'=>'One investor invests the full product amount.'],
        ['icon'=>'fa-truck-fast',          'num'=>'03','title'=>'Product Imported',   'desc'=>'We import the product from China. (Estimated 25 Days)'],
        ['icon'=>'fa-store',               'num'=>'04','title'=>'Product Sold',       'desc'=>'After arrival, product selling starts.'],
        ['icon'=>'fa-calendar-check',      'num'=>'05','title'=>'Weekly Profit',      'desc'=>'You will receive profit every week.'],
        ['icon'=>'fa-circle-check',        'num'=>'06','title'=>'Completed',          'desc'=>'After full profit & capital return, you can re-invest.'],
      ] as $i => $step)
        <div class="col-6 col-md-4 col-lg-2 text-center step-card" data-aos="fade-up" data-aos-delay="{{ $i * 80 }}">
          <div class="step-icon"><i class="fas {{ $step['icon'] }}"></i></div>
          <div class="step-num">{{ $step['num'] }}</div>
          <div class="step-title">{{ $step['title'] }}</div>
          <p class="step-desc mb-0">{{ $step['desc'] }}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ─────────────── WHY INVEST WITH US ─────────────── --}}
<section class="py-5" style="background:#0F172A;" id="whyUs">
  <div class="container">
    <div class="text-center mb-5" data-aos="fade-up">
      <h2 class="fw-bold text-white mb-1">Why Invest With Us?</h2>
      <p class="text-secondary small mb-0">Built for security, transparency, and consistent weekly returns</p>
    </div>
    <div class="row g-4">
      @foreach([
        ['icon'=>'fa-shield-halved','color'=>'#38BDF8','title'=>'Transparent Process','desc'=>'Complete transparency in every import step, custom clearance, and sales reporting.'],
        ['icon'=>'fa-wallet',       'color'=>'#FACC15','title'=>'Weekly Profit',       'desc'=>'Profit payouts every week directly to your bank or mobile wallet after sales start.'],
        ['icon'=>'fa-lock',         'color'=>'#4ADE80','title'=>'Secure Investment',   'desc'=>'Single product & single investor model ensuring clear ownership and security.'],
        ['icon'=>'fa-award',        'color'=>'#C084FC','title'=>'Proven Track Record', 'desc'=>'Successful imports & happy investors across multiple product batches.'],
      ] as $i => $w)
        <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="{{ $i * 80 }}">
          <div class="why-card">
            <div class="why-icon" style="color:{{ $w['color'] }}"><i class="fas {{ $w['icon'] }}"></i></div>
            <h6 class="fw-bold text-white mb-2">{{ $w['title'] }}</h6>
            <p class="text-secondary small mb-0">{{ $w['desc'] }}</p>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

@endsection
