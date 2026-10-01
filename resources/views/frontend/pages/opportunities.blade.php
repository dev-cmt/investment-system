@extends('frontend.layouts.master')

@push('styles')
<style>
  /* ── Active Opportunities Page ── */
  .page-hero {
    background: linear-gradient(135deg, #f0fdf8 0%, #e8f5ee 50%, #d1fae5 100%);
    padding: 80px 0 60px; position: relative; overflow: hidden;
  }
  .page-hero::before {
    content: ''; position: absolute; top: -120px; right: -120px;
    width: 500px; height: 500px;
    background: radial-gradient(circle, rgba(26,158,79,0.08) 0%, transparent 70%);
    border-radius: 50%;
  }
  .page-hero-badge {
    display: inline-flex; align-items: center; gap: 8px;
    background: var(--green-light); color: var(--green-primary);
    font-size: 0.78rem; font-weight: 700; padding: 6px 16px;
    border-radius: 30px; border: 1px solid rgba(26,158,79,0.25);
    margin-bottom: 16px; letter-spacing: 0.5px; text-transform: uppercase;
  }
  .page-hero-badge .pulse-dot {
    width: 8px; height: 8px; background: var(--green-primary);
    border-radius: 50%; animation: blink 1.2s infinite; flex-shrink: 0;
  }
  .page-hero-title { font-size: 2.8rem; font-weight: 800; color: var(--text-dark); letter-spacing: -0.8px; line-height: 1.15; margin-bottom: 18px; }
  .page-hero-title span { color: var(--green-primary); }
  .page-hero-desc { font-size: 1.05rem; color: var(--text-muted); line-height: 1.75; max-width: 560px; }
  .page-breadcrumb { display: flex; align-items: center; gap: 8px; font-size: 0.82rem; color: var(--text-muted); margin-top: 20px; }
  .page-breadcrumb a { color: var(--green-primary); font-weight: 600; text-decoration: none; }
  .page-breadcrumb i { font-size: 0.65rem; }

  /* Filters bar */
  .filter-bar {
    background: #fff; border-bottom: 1px solid var(--border-color);
    padding: 16px 0; position: sticky; top: 70px; z-index: 99;
  }
  .filter-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 18px; border-radius: 30px; font-size: 0.83rem; font-weight: 600;
    border: 1px solid var(--border-color); background: #fff; color: var(--text-muted);
    cursor: pointer; transition: var(--transition);
  }
  .filter-btn.active, .filter-btn:hover { background: var(--green-primary); color: #fff; border-color: var(--green-primary); }

  /* Opportunities Grid */
  .opportunities-page { padding: 60px 0 80px; }
  .opp-card {
    background: #fff; border: 1px solid var(--border-color);
    border-radius: 14px; padding: 18px; transition: var(--transition);
    height: 100%; display: flex; flex-direction: column; justify-content: space-between;
  }
  .opp-card:hover { box-shadow: var(--card-shadow-h); transform: translateY(-3px); border-color: rgba(26,158,79,0.2); }
  .opp-img-wrap {
    border-radius: 10px; background: #f6f8fa; overflow: hidden;
    height: 160px; display: flex; align-items: center; justify-content: center;
  }
  .opp-img-wrap img { width: 100%; height: 160px; object-fit: cover; transition: var(--transition); }
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

  /* Login Prompt Banner */
  .login-prompt-banner {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    border-radius: 20px; padding: 50px 40px; text-align: center;
    color: #fff; margin-bottom: 50px;
  }
  .login-prompt-banner h2 { font-size: 1.8rem; font-weight: 800; margin-bottom: 12px; }
  .login-prompt-banner p { font-size: 0.95rem; color: #94a3b8; max-width: 500px; margin: 0 auto 24px; line-height: 1.7; }

  /* Empty state */
  .empty-state { text-align: center; padding: 80px 20px; }
  .empty-state-icon { font-size: 4rem; color: var(--green-light); margin-bottom: 20px; }
  .empty-state h3 { font-size: 1.3rem; font-weight: 700; color: var(--text-dark); margin-bottom: 10px; }
  .empty-state p { font-size: 0.9rem; color: var(--text-muted); }

  @media(max-width:768px) {
    .page-hero-title { font-size: 2rem; }
    .filter-bar { top: 60px; }
  }
</style>
@endpush

@section('content')

  @php
    $hero = $content['hero'] ?? [];
    $guestBanner = $content['guest_banner'] ?? [];
    $emptyState = $content['empty_state'] ?? [];
    $types = !empty($availableTypes) ? $availableTypes : ['Import', 'Local', 'Manufacture'];
  @endphp

  {{-- ── Page Hero ── --}}
  <section class="page-hero">
    <div class="container">
      @if(!empty($hero['show_badge'] ?? true))
        <div class="page-hero-badge">
          <span class="pulse-dot"></span> {{ $hero['badge_text'] ?? 'Live Opportunities' }}
        </div>
      @endif
      <h1 class="page-hero-title">Active <span>Opportunities</span></h1>
      <div class="d-flex align-items-center gap-3 mt-4 flex-wrap">
        <div style="background:var(--green-light);color:var(--green-primary);border-radius:10px;padding:10px 18px;display:inline-flex;align-items:center;gap:10px;">
          <i class="fas fa-fire"></i>
          <span style="font-weight:700;font-size:0.88rem;">{{ $posts->count() }} Active {{ Str::plural('Opportunity', $posts->count()) }}</span>
        </div>
        <div style="font-size:0.82rem;color:var(--text-muted);display:flex;align-items:center;gap:6px;">
          <i class="fas fa-clock text-success"></i> Updated in real time
        </div>
      </div>
      <nav class="page-breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <i class="fas fa-chevron-right"></i>
        <span>Active Opportunities</span>
      </nav>
    </div>
  </section>

  {{-- ── Filter Bar ── --}}
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

  {{-- ── Opportunities ── --}}
  <section class="opportunities-page">
    <div class="container">

      {{-- Guest Login Prompt --}}
      @guest
        @if($guestBanner['show'] ?? true)
          <div class="login-prompt-banner" data-aos="fade-up">
            <i class="fas fa-lock-open" style="font-size:2.5rem;color:#34d399;margin-bottom:16px;display:block;"></i>
            <h2>{{ $guestBanner['title'] ?? 'Sign In to View Full Details & Invest' }}</h2>
            <p>{{ $guestBanner['text'] ?? 'Create a free account or log in to see full investment specifications, funding progress, and to place your investment bid.' }}</p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
              <a href="{{ route('register') }}" class="btn btn-success rounded-pill px-5 py-2.5 fw-bold shadow">
                <i class="fas fa-user-plus me-2"></i>{{ $guestBanner['btn1_text'] ?? 'Create Free Account' }}
              </a>
              <a href="{{ route('login') }}" class="btn btn-outline-light rounded-pill px-5 py-2.5 fw-bold">
                <i class="fas fa-sign-in-alt me-2"></i>{{ $guestBanner['btn2_text'] ?? 'Sign In' }}
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
               data-aos="fade-up" data-aos-delay="{{ $index * 80 }}">
            <div class="opp-card w-100">

              {{-- Meta row --}}
              <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-center gap-1.5">
                  <span class="badge-active"><i class="fa-solid fa-circle-dot fa-beat" style="font-size:0.6rem;"></i> Active</span>
                  <span class="badge {{ $post->type === 'Local' ? 'bg-warning-subtle text-dark border border-warning' : ($post->type === 'Manufacture' ? 'bg-info-subtle text-dark border border-info' : 'bg-success-subtle text-success border border-success-subtle') }} rounded-pill px-2.5 py-1 extra-small fw-bold">
                    <i class="fas {{ $post->type === 'Local' ? 'fa-location-dot' : ($post->type === 'Manufacture' ? 'fa-industry' : 'fa-ship') }} me-1"></i>{{ $post->type ?? 'Import' }}
                  </span>
                </div>
                <span class="posted-date">{{ $post->created_at->format('d M Y') }}</span>
              </div>

              {{-- Image --}}
              @php $galleryUrls = $post->gallery_image_urls; @endphp
              <div class="opp-img-wrap mb-3 position-relative">
                @if(count($galleryUrls) > 0)
                  <img src="{{ $galleryUrls[0] }}" alt="{{ $post->title }}" />
                @else
                  <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:var(--text-muted);font-size:2rem;">
                    <i class="fas fa-box-archive"></i>
                  </div>
                @endif
                @if(count($galleryUrls) > 1)
                  <span class="position-absolute bottom-0 end-0 mb-1 me-1 px-2 py-0.5 rounded-pill bg-dark bg-opacity-75 text-white extra-small fw-semibold"
                    style="font-size: 0.65rem; z-index: 2;">
                    <i class="fas fa-images me-1"></i>{{ count($galleryUrls) }}
                  </span>
                @endif
              </div>

              {{-- Title + Specs --}}
              <h2 class="product-title mb-2" style="font-size:1rem;">{{ $post->title }}</h2>
              <ul class="spec-list mb-3">
                <li>
                  <span class="spec-label"><i class="fas fa-boxes-stacked"></i> Quantity</span>
                  <span class="spec-val">{{ number_format($post->total_quantity) }} pcs</span>
                </li>
                <li>
                  <span class="spec-label"><i class="fas fa-tag"></i> Cost/Piece</span>
                  <span class="spec-val">&#2547;{{ number_format($post->unit_cost) }}</span>
                </li>
                <li>
                  <span class="spec-label"><i class="fas fa-money-bill-wave"></i> Total Investment</span>
                  <span class="spec-val">&#2547;{{ number_format($post->target_amount) }}</span>
                </li>
                <li>
                  <span class="spec-label"><i class="fas fa-chart-line"></i> Profit/Piece</span>
                  <span class="spec-val green">&#2547;{{ number_format($post->profit_per_unit) }}</span>
                </li>
                <li>
                  <span class="spec-label"><i class="fas fa-calendar-days"></i> {{ $post->time_label }}</span>
                  <span class="spec-val">{{ $post->expected_import_days }} Days</span>
                </li>
              </ul>

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

              {{-- CTA --}}
              @auth
                <a href="{{ route('opportunity.show', $post->id) }}"
                   class="btn-invest d-block text-center mt-3 text-decoration-none"
                   style="border-radius:8px;">
                  View Details &amp; Invest
                </a>
              @else
                <a href="{{ route('login') }}"
                   class="btn-invest d-block text-center mt-3 text-decoration-none"
                   style="border-radius:8px;background:linear-gradient(135deg,#475569,#334155);">
                  <i class="fas fa-lock me-1"></i> Login to Invest
                </a>
              @endauth

            </div>
          </div>
        @empty
          <div class="col-12">
            <div class="empty-state">
              <div class="empty-state-icon"><i class="fas fa-box-open"></i></div>
              <h3>{{ $emptyState['title'] ?? 'No Active Opportunities Right Now' }}</h3>
              <p>{{ $emptyState['text'] ?? "We're sourcing the next batch of investment products. Check back soon or register to be notified." }}</p>
              @guest
                <a href="{{ route('register') }}" class="btn btn-success rounded-pill px-5 py-2 fw-bold mt-3">
                  <i class="fas fa-bell me-2"></i>{{ $emptyState['btn_text'] ?? 'Get Notified' }}
                </a>
              @endguest
            </div>
          </div>
        @endforelse
      </div>
    </div>
  </section>

@endsection

@push('scripts')
<script>
  // Filter buttons
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
    });
  });
</script>
@endpush

