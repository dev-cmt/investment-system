@extends('frontend.layouts.master')

@push('styles')
<style>
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
  .page-hero-title { font-size: 2.8rem; font-weight: 800; color: var(--text-dark); letter-spacing: -0.8px; line-height: 1.15; margin-bottom: 18px; }
  .page-hero-title span { color: var(--green-primary); }
  .page-hero-desc { font-size: 1.05rem; color: var(--text-muted); line-height: 1.75; max-width: 560px; }
  .page-breadcrumb { display: flex; align-items: center; gap: 8px; font-size: 0.82rem; color: var(--text-muted); margin-top: 20px; }
  .page-breadcrumb a { color: var(--green-primary); font-weight: 600; text-decoration: none; }
  .page-breadcrumb i { font-size: 0.65rem; }

  /* Policy Content */
  .policy-section { padding: 70px 0; }
  .policy-sidebar {
    position: sticky; top: 90px;
    background: #fff; border: 1px solid var(--border-color);
    border-radius: 16px; padding: 24px; box-shadow: 0 2px 16px rgba(0,0,0,0.04);
  }
  .policy-sidebar-title { font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; color: var(--text-muted); margin-bottom: 16px; }
  .policy-toc { list-style: none; padding: 0; margin: 0; }
  .policy-toc li { margin-bottom: 4px; }
  .policy-toc a {
    display: block; padding: 8px 12px; border-radius: 8px; font-size: 0.85rem;
    font-weight: 500; color: var(--text-muted); text-decoration: none;
    transition: var(--transition); border-left: 3px solid transparent;
  }
  .policy-toc a:hover { color: var(--green-primary); background: var(--green-xlight); border-left-color: var(--green-primary); }

  .policy-body { background: #fff; border: 1px solid var(--border-color); border-radius: 20px; padding: 48px; }
  .policy-last-updated { font-size: 0.8rem; color: var(--text-muted); margin-bottom: 30px; display: flex; align-items: center; gap: 8px; background: #f9fafb; border-radius: 8px; padding: 10px 14px; }
  .policy-section-block { margin-bottom: 40px; padding-bottom: 40px; border-bottom: 1px solid var(--border-color); }
  .policy-section-block:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
  .policy-section-block h2 {
    font-size: 1.3rem; font-weight: 800; color: var(--text-dark);
    margin-bottom: 14px; display: flex; align-items: center; gap: 10px;
  }
  .policy-section-block h2 .policy-num {
    width: 32px; height: 32px; border-radius: 8px; background: var(--green-light);
    color: var(--green-primary); font-size: 0.78rem; font-weight: 800;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
  }
  .policy-section-block p { font-size: 0.9rem; color: var(--text-muted); line-height: 1.8; margin-bottom: 12px; white-space: pre-line; }

  @media(max-width:768px) {
    .page-hero-title { font-size: 2rem; }
    .policy-body { padding: 24px; }
    .policy-sidebar { position: static; margin-bottom: 24px; }
  }
</style>
@endpush

@section('content')

  @php
    $hero = $content['hero'] ?? [];
    $intro = str_replace(
      ['{company}', '{email}', '{phone}', '{address}'],
      [$companyName, $setting->email ?? 'info@investhub.com', $setting->phone ?? '', $setting->address ?? ''],
      $content['intro'] ?? "These Terms and Conditions govern your access to and use of {$companyName}."
    );
    $sections = $content['sections'] ?? [];
  @endphp

  {{-- ── Page Hero ── --}}
  <section class="page-hero">
    <div class="container">
      @if(!empty($hero['show_badge']))
        <div class="page-hero-badge">
          <i class="{{ $hero['badge_icon'] ?? 'fas fa-scale-balanced' }}"></i> {{ $hero['badge_text'] ?? 'Legal Document' }}
        </div>
      @endif
      <h1 class="page-hero-title">Terms &amp; <span>Conditions</span></h1>
      <nav class="page-breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <i class="fas fa-chevron-right"></i>
        <span>Terms & Conditions</span>
      </nav>
    </div>
  </section>

  {{-- ── Terms Content ── --}}
  <section class="policy-section">
    <div class="container">
      <div class="row g-5">

        <!-- Sidebar TOC -->
        @if(!empty($sections))
          <div class="col-lg-3 d-none d-lg-block">
            <div class="policy-sidebar" data-aos="fade-right">
              <div class="policy-sidebar-title">Contents</div>
              <ul class="policy-toc">
                @foreach($sections as $sec)
                  <li>
                    <a href="#{{ $sec['id'] ?? 'tc-sec-' . $loop->index }}">
                      {{ $sec['num'] ?? $loop->iteration }}. {{ $sec['title'] ?? '' }}
                    </a>
                  </li>
                @endforeach
              </ul>
            </div>
          </div>
        @endif

        <!-- Body -->
        <div class="{{ !empty($sections) ? 'col-lg-9' : 'col-12' }}" data-aos="fade-up">
          <div class="policy-body">
            <div class="policy-last-updated">
              <i class="fas fa-calendar-check text-success"></i>
              Last updated: {{ $page->updated_at ? $page->updated_at->format('F d, Y') : date('F d, Y') }} &nbsp;|&nbsp;
              <i class="fas fa-building text-success"></i>
              {{ $companyName }}
            </div>

            @if(!empty($intro))
              <div class="policy-section-block" id="tc-intro">
                <p>{{ $intro }}</p>
              </div>
            @endif

            @foreach($sections as $sec)
              @php
                $secBody = str_replace(
                  ['{company}', '{email}', '{phone}', '{address}'],
                  [$companyName, $setting->email ?? 'info@investhub.com', $setting->phone ?? '', $setting->address ?? ''],
                  $sec['body'] ?? ''
                );
              @endphp
              <div class="policy-section-block" id="{{ $sec['id'] ?? 'tc-sec-' . $loop->index }}">
                <h2><span class="policy-num">{{ $sec['num'] ?? sprintf('%02d', $loop->iteration) }}</span> {{ $sec['title'] ?? '' }}</h2>
                <p>{!! nl2br(e($secBody)) !!}</p>
              </div>
            @endforeach

          </div>
        </div>
      </div>
    </div>
  </section>

@endsection

