@extends('frontend.layouts.master')

@push('styles')
<style>
  /* ── About Us Page ── */
  .page-hero {
    background: linear-gradient(135deg, #f0fdf8 0%, #e8f5ee 50%, #d1fae5 100%);
    padding: 80px 0 60px;
    position: relative;
    overflow: hidden;
  }
  .page-hero::before {
    content: '';
    position: absolute; top: -120px; right: -120px;
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
  .page-hero-title {
    font-size: 2.8rem; font-weight: 800; color: var(--text-dark);
    letter-spacing: -0.8px; line-height: 1.15; margin-bottom: 18px;
  }
  .page-hero-title span { color: var(--green-primary); }
  .page-hero-desc {
    font-size: 1.05rem; color: var(--text-muted);
    line-height: 1.75; max-width: 560px;
  }
  .page-breadcrumb {
    display: flex; align-items: center; gap: 8px;
    font-size: 0.82rem; color: var(--text-muted); margin-top: 20px;
  }
  .page-breadcrumb a { color: var(--green-primary); font-weight: 600; text-decoration: none; }
  .page-breadcrumb i { font-size: 0.65rem; }

  /* Story section */
  .about-story { padding: 70px 0; background: #f9fafb; }
  .story-img-wrap {
    border-radius: 20px; overflow: hidden; position: relative;
    box-shadow: 0 8px 40px rgba(0,0,0,0.1);
  }
  .story-img-wrap img { width: 100%; height: 380px; object-fit: cover; }
  .story-badge {
    position: absolute; bottom: 20px; left: 20px;
    background: var(--white); border-radius: 12px; padding: 12px 18px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    display: flex; align-items: center; gap: 12px;
  }
  .story-badge-icon { width: 40px; height: 40px; background: var(--green-light); border-radius: 10px;
    display: flex; align-items: center; justify-content: center; color: var(--green-primary); font-size: 1rem; }
  .story-badge-num { font-size: 1.3rem; font-weight: 800; color: var(--text-dark); line-height: 1; }
  .story-badge-sub { font-size: 0.73rem; color: var(--text-muted); }
  .about-section-label { font-size: 0.78rem; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: var(--green-primary); margin-bottom: 8px; }
  .about-section-title { font-size: 2rem; font-weight: 800; color: var(--text-dark); letter-spacing: -0.5px; margin-bottom: 16px; }
  .about-section-text { font-size: 0.95rem; color: var(--text-muted); line-height: 1.8; margin-bottom: 14px; }

  /* Values */
  .values-section { padding: 70px 0; background: #0F172A; }
  .value-card {
    background: #1E293B; border-radius: 16px; padding: 28px;
    height: 100%; transition: var(--transition); border: 1px solid rgba(255,255,255,0.04);
  }
  .value-card:hover { background: #243448; transform: translateY(-4px); border-color: rgba(26,158,79,0.2); }
  .value-icon { font-size: 2rem; margin-bottom: 16px; }
  .value-title { font-size: 1rem; font-weight: 700; color: #fff; margin-bottom: 10px; }
  .value-text { font-size: 0.85rem; color: #94a3b8; line-height: 1.7; }

  /* Process steps */
  .process-section { padding: 70px 0; background: var(--white); }
  .process-step {
    display: flex; gap: 20px; align-items: flex-start;
    padding: 24px; border-radius: 14px; border: 1px solid var(--border-color);
    background: #fff; transition: var(--transition); height: 100%;
  }
  .process-step:hover { box-shadow: var(--card-shadow); border-color: rgba(26,158,79,0.2); }
  .process-step-num {
    width: 48px; height: 48px; border-radius: 12px; flex-shrink: 0;
    background: var(--green-primary); color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; font-weight: 800;
  }
  .process-step-title { font-size: 0.95rem; font-weight: 700; color: var(--text-dark); margin-bottom: 6px; }
  .process-step-text { font-size: 0.83rem; color: var(--text-muted); line-height: 1.65; }

  /* CTA */
  .about-cta { padding: 80px 0; background: linear-gradient(135deg, #16a34a 0%, #15803d 50%, #14532d 100%); position: relative; overflow: hidden; }
  .about-cta::before { content: ''; position: absolute; top: -60px; right: -60px; width: 300px; height: 300px; background: rgba(255,255,255,0.04); border-radius: 50%; }
  .about-cta-title { font-size: 2.2rem; font-weight: 800; color: #fff; letter-spacing: -0.5px; margin-bottom: 14px; }
  .about-cta-text { font-size: 1rem; color: rgba(255,255,255,0.7); max-width: 540px; margin: 0 auto 30px; line-height: 1.7; }

  @media(max-width:768px) {
    .page-hero-title { font-size: 2rem; }
    .about-section-title { font-size: 1.6rem; }
    .story-img-wrap img { height: 260px; }
    .about-cta-title { font-size: 1.8rem; }
  }
</style>
@endpush

@section('content')

  @php
    $hero = $content['hero'] ?? [];
    $stats = $content['stats'] ?? [];
    $story = $content['story'] ?? [];
    $values = $content['values'] ?? [];
    $process = $content['process'] ?? [];
    $cta = $content['cta'] ?? [];

    $heroTitle = str_replace('{company}', $companyName, $hero['title'] ?? 'About ' . $companyName);
  @endphp

  {{-- ── Page Hero ── --}}
  <section class="page-hero">
    <div class="container">
      @if(!empty($hero['show_badge']))
        <div class="page-hero-badge">
          <i class="{{ $hero['badge_icon'] ?? 'fas fa-building' }}"></i> {{ $hero['badge_text'] ?? 'Our Company' }}
        </div>
      @endif
      <h1 class="page-hero-title">{!! nl2br(e($heroTitle)) !!}</h1>
      <nav class="page-breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <i class="fas fa-chevron-right"></i>
        <span>About Us</span>
      </nav>
    </div>
  </section>

  {{-- ── Stats ── --}}
  @if(!empty($stats['show_section'] ?? true))
    @php
      $autoCalc = $stats['auto_calculate'] ?? true;
    @endphp
    <section class="stats-section py-5 bg-white border-bottom">
      <div class="container">
        <div class="row g-4">
          <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="0">
            <div class="stat-card p-4 rounded-4 bg-light text-center border">
              <div class="stat-icon mb-2 text-success fs-3"><i class="fas fa-users"></i></div>
              <div class="stat-number fs-2 fw-bold text-dark">{{ $autoCalc ? number_format($dbStats['investors'] ?? 1) : ($stats['items'][0]['number'] ?? '1,000+') }}<span style="color:var(--green-primary)">+</span></div>
              <div class="stat-label text-muted small fw-medium">{{ $stats['items'][0]['label'] ?? 'Active Investors' }}</div>
            </div>
          </div>
          <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="80">
            <div class="stat-card p-4 rounded-4 bg-light text-center border">
              <div class="stat-icon mb-2 text-success fs-3"><i class="fas fa-box-archive"></i></div>
              <div class="stat-number fs-2 fw-bold text-dark">{{ $autoCalc ? number_format($dbStats['products'] ?? 1) : ($stats['items'][1]['number'] ?? '50+') }}<span style="color:var(--green-primary)">+</span></div>
              <div class="stat-label text-muted small fw-medium">{{ $stats['items'][1]['label'] ?? 'Investment Products' }}</div>
            </div>
          </div>
          <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="160">
            <div class="stat-card p-4 rounded-4 bg-light text-center border">
              <div class="stat-icon mb-2 text-success fs-3"><i class="fas fa-money-bill-trend-up"></i></div>
              <div class="stat-number fs-2 fw-bold text-dark">{{ $autoCalc ? ($dbStats['total_invested'] ?? '৳1 Cr+') : ($stats['items'][2]['number'] ?? '৳5 Cr+') }}</div>
              <div class="stat-label text-muted small fw-medium">{{ $stats['items'][2]['label'] ?? 'Total Invested' }}</div>
            </div>
          </div>
          <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="240">
            <div class="stat-card p-4 rounded-4 bg-light text-center border">
              <div class="stat-icon mb-2 text-success fs-3"><i class="fas fa-calendar-check"></i></div>
              <div class="stat-number fs-2 fw-bold text-dark">{{ $autoCalc ? ($dbStats['years'] ?? '3+ Years') : ($stats['items'][3]['number'] ?? '3+ Years') }}</div>
              <div class="stat-label text-muted small fw-medium">{{ $stats['items'][3]['label'] ?? 'Years of Trust' }}</div>
            </div>
          </div>
        </div>
      </div>
    </section>
  @endif

  {{-- ── Our Story ── --}}
  @if($story['show_section'] ?? true)
    <section class="about-story">
      <div class="container">
        <div class="row g-5 align-items-center">
          <div class="col-lg-5" data-aos="fade-right">
            <div class="story-img-wrap">
              @if(!empty($setting->logo_url))
                <div style="height:380px;background:#fff;display:flex;align-items:center;justify-content:center;padding:40px;">
                  <img src="{{ $setting->logo_url }}" alt="{{ $companyName }}" style="max-height:180px;object-fit:contain;" />
                </div>
              @else
                <img src="{{ asset($story['image'] ?? 'images/hero.jpg') }}" alt="{{ $companyName }}" onerror="this.style.background='linear-gradient(135deg,#e8f5ee,#d1fae5)';this.style.height='380px';this.removeAttribute('onerror');" />
              @endif
              <div class="story-badge">
                <div class="story-badge-icon"><i class="fas fa-handshake"></i></div>
                <div>
                  <div class="story-badge-num">{{ $story['badge_num'] ?? '100%' }}</div>
                  <div class="story-badge-sub">{{ $story['badge_sub'] ?? 'Transparent Returns' }}</div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-7" data-aos="fade-left">
            <div class="about-section-label">{{ $story['label'] ?? 'Our Story' }}</div>
            <h2 class="about-section-title">{!! nl2br(e($story['title'] ?? "Built on Trust,\nDriven by Results")) !!}</h2>
            @if(isset($story['paragraphs']) && is_array($story['paragraphs']))
              @foreach($story['paragraphs'] as $para)
                <p class="about-section-text">{{ str_replace('{company}', $companyName, $para) }}</p>
              @endforeach
            @endif
            @if(isset($story['bullets']) && is_array($story['bullets']))
              <div class="d-flex flex-wrap gap-3 mt-4">
                @foreach($story['bullets'] as $bullet)
                  <div class="d-flex align-items-center gap-2 text-success fw-semibold" style="font-size:0.88rem;">
                    <i class="fas fa-circle-check"></i> {{ $bullet }}
                  </div>
                @endforeach
              </div>
            @endif
          </div>
        </div>
      </div>
    </section>
  @endif

  {{-- ── Our Values ── --}}
  @if(($values['show_section'] ?? true) && !empty($values['items']))
    <section class="values-section">
      <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
          <div style="font-size:0.78rem;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:var(--green-primary);margin-bottom:8px;">{{ $values['label'] ?? 'What We Stand For' }}</div>
          <h2 style="font-size:2rem;font-weight:800;color:#fff;letter-spacing:-0.5px;">{{ $values['title'] ?? 'Our Core Values' }}</h2>
          @if(!empty($values['subtitle']))
            <p style="font-size:0.9rem;color:#94a3b8;max-width:500px;margin:10px auto 0;">{{ $values['subtitle'] }}</p>
          @endif
        </div>
        <div class="row g-4">
          @foreach($values['items'] as $i => $val)
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="{{ $i * 80 }}">
              <div class="value-card">
                <div class="value-icon" style="color:{{ $val['color'] ?? '#34D399' }}"><i class="{{ $val['icon'] ?? 'fas fa-shield-halved' }}"></i></div>
                <div class="value-title">{{ $val['title'] ?? '' }}</div>
                <div class="value-text">{{ str_replace('{company}', $companyName, $val['text'] ?? '') }}</div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </section>
  @endif

  {{-- ── How We Work / Process ── --}}
  @if(($process['show_section'] ?? true) && !empty($process['steps']))
    <section class="process-section">
      <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
          <div class="about-section-label">{{ $process['label'] ?? 'Our Process' }}</div>
          <h2 class="about-section-title">{{ $process['title'] ?? 'How We Operate' }}</h2>
          @if(!empty($process['subtitle']))
            <p style="font-size:0.9rem;color:var(--text-muted);max-width:500px;margin:10px auto 0;">{{ $process['subtitle'] }}</p>
          @endif
        </div>
        <div class="row g-4">
          @foreach($process['steps'] as $i => $step)
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $i * 70 }}">
              <div class="process-step">
                <div class="process-step-num">{{ $step['num'] ?? sprintf('%02d', $i+1) }}</div>
                <div>
                  <div class="process-step-title">{{ $step['title'] ?? '' }}</div>
                  <div class="process-step-text">{{ str_replace('{company}', $companyName, $step['text'] ?? '') }}</div>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </section>
  @endif

  {{-- ── CTA ── --}}
  @if($cta['show_section'] ?? true)
    <section class="about-cta text-center" data-aos="zoom-in">
      <div class="container position-relative" style="z-index:2;">
        <div class="about-cta-title">{{ $cta['title'] ?? 'Ready to Invest With Us?' }}</div>
        <p class="about-cta-text">
          {{ str_replace('{company}', $companyName, $cta['subtitle'] ?? 'Join verified investors earning reliable weekly returns.') }}
        </p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
          @guest
            <a href="{{ route('register') }}" class="btn btn-light rounded-pill px-5 py-2.5 fw-bold text-success shadow-lg">
              <i class="fas fa-user-plus me-2"></i>{{ $cta['btn1_text'] ?? 'Create Free Account' }}
            </a>
          @endguest
          <a href="{{ route('page.opportunities') }}" class="btn btn-outline-light rounded-pill px-5 py-2.5 fw-bold">
            <i class="fas fa-fire me-2"></i>{{ $cta['btn2_text'] ?? 'View Opportunities' }}
          </a>
        </div>
      </div>
    </section>
  @endif

@endsection

