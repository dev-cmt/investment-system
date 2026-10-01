<x-backend-layout>

@push('styles')
<style>
    /* ── Page Content Editor Styles ─────────────────────────── */
    .tab-pill-nav .nav-link {
        color: #4b5563;
        font-weight: 600;
        font-size: 0.82rem;
        border-radius: 8px;
        padding: 8px 14px;
        display: flex;
        align-items: center;
        gap: 7px;
        transition: all 0.2s ease;
        border: 1px solid transparent;
        white-space: nowrap;
    }
    .tab-pill-nav .nav-link.active {
        background-color: #1a9e4f;
        color: #fff;
        box-shadow: 0 4px 12px rgba(26,158,79,0.22);
    }
    .tab-pill-nav .nav-link:hover:not(.active) {
        background: #f3f4f6;
        color: #111827;
        border-color: #e5e7eb;
    }
    .tab-pill-nav .nav-link i { width: 14px; text-align: center; }
    .section-toggle-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: linear-gradient(135deg, #f8fafc 0%, #f0fdf4 100%);
        border: 1px solid #d1fae5;
        padding: 12px 18px;
        border-radius: 10px;
        margin-bottom: 22px;
    }
    .section-toggle-bar strong { font-size: 0.9rem; color: #111827; }
    .section-toggle-bar small  { color: #6b7280; font-size: 0.78rem; }
    .repeater-item {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 16px;
        position: relative;
        transition: box-shadow 0.2s, border-color 0.2s, background 0.2s;
    }
    .repeater-item:hover {
        background: #fff;
        border-color: #d1d5db;
        box-shadow: 0 4px 14px rgba(0,0,0,0.06);
    }
    .repeater-remove {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
    }
    .dark-repeater-item {
        background: #1e293b;
        border: 1px solid #334155;
        border-radius: 10px;
        padding: 16px;
        position: relative;
        transition: all 0.2s;
    }
    .dark-repeater-item:hover { background: #243448; border-color: #475569; }
    .hero-img-preview {
        width: 100%;
        max-height: 200px;
        object-fit: cover;
        border-radius: 10px;
        border: 2px dashed #d1d5db;
        background: #f9fafb;
    }
    .icon-input-group .input-group-text { min-width: 38px; justify-content: center; }
    .fld-label {
        font-size: 0.79rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 5px;
        display: block;
    }
    .fld-label-light {
        font-size: 0.79rem;
        font-weight: 600;
        color: #cbd5e1;
        margin-bottom: 5px;
        display: block;
    }
    .sticky-save-bar {
        position: sticky;
        bottom: 14px;
        z-index: 100;
        margin-top: 24px;
    }
    .sticky-save-bar .inner {
        background: rgba(255,255,255,0.97);
        backdrop-filter: blur(10px);
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 12px 20px;
        box-shadow: 0 8px 30px -6px rgba(0,0,0,0.12);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }
</style>
@endpush

@php
    $pageTitles = [
        'home'          => 'Home Page',
        'about'         => 'About Us Page',
        'contact'       => 'Contact Us Page',
        'privacy'       => 'Privacy Policy Page',
        'terms'         => 'Terms & Conditions Page',
        'opportunities' => 'Active Opportunities Page',
    ];
    $pageRoutes = [
        'home'          => route('home'),
        'about'         => route('page.about'),
        'contact'       => route('page.contact'),
        'privacy'       => route('page.privacy'),
        'terms'         => route('page.terms'),
        'opportunities' => route('page.opportunities'),
    ];
    $currentTitle = $pageTitles[$slug] ?? ucfirst($slug) . ' Page';
    $liveUrl = $pageRoutes[$slug] ?? route('home');
@endphp

<div class="inv-page-container">

    {{-- ── 1. Hero Header ───────────────────────────────────────────── --}}
    <div class="inv-hero-header">
        <div class="d-flex align-items-center gap-3">
            <div class="inv-title-badge">
                <i class="fas fa-file-waveform"></i>
            </div>
            <div>
                <div class="d-flex align-items-center gap-2">
                    <h4 class="fw-bold mb-0 text-dark">{{ $currentTitle }} Content Manager</h4>
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 extra-small fw-semibold">
                        <i class="fas fa-circle fa-beat-fade" style="font-size:0.5rem;"></i> Live
                    </span>
                </div>
                <p class="text-muted small mb-0 mt-0.5">Control every section, text, badge, and media on the public-facing {{ strtolower($currentTitle) }}.</p>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ $liveUrl }}" target="_blank" class="btn btn-outline-secondary btn-sm rounded-3 px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-2">
                <i class="fas fa-arrow-up-right-from-square"></i>
                <span class="d-none d-sm-inline">View Live</span>
            </a>
            <button type="button" class="btn btn-outline-danger btn-sm rounded-3 px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-2"
                    data-bs-toggle="modal" data-bs-target="#resetModal">
                <i class="fas fa-rotate-left"></i>
                <span class="d-none d-sm-inline">Reset Defaults</span>
            </button>
        </div>
    </div>

    {{-- ── Page Switcher Pills ──────────────────────────────────────── --}}
    <div class="d-flex align-items-center gap-2 mb-4 overflow-auto pb-1 flex-nowrap">
        <a href="{{ route('settings.pages-content.index', ['slug' => 'home']) }}" class="btn {{ $slug === 'home' ? 'btn-success text-white shadow-sm' : 'btn-outline-secondary' }} btn-sm rounded-pill px-3.5 py-1.5 fw-semibold d-inline-flex align-items-center gap-1.5 text-nowrap">
            <i class="fas fa-house"></i> Home Page
        </a>
        <a href="{{ route('settings.pages-content.index', ['slug' => 'about']) }}" class="btn {{ $slug === 'about' ? 'btn-success text-white shadow-sm' : 'btn-outline-secondary' }} btn-sm rounded-pill px-3.5 py-1.5 fw-semibold d-inline-flex align-items-center gap-1.5 text-nowrap">
            <i class="fas fa-building"></i> About Us
        </a>
        <a href="{{ route('settings.pages-content.index', ['slug' => 'contact']) }}" class="btn {{ $slug === 'contact' ? 'btn-success text-white shadow-sm' : 'btn-outline-secondary' }} btn-sm rounded-pill px-3.5 py-1.5 fw-semibold d-inline-flex align-items-center gap-1.5 text-nowrap">
            <i class="fas fa-headset"></i> Contact Us
        </a>
        <a href="{{ route('settings.pages-content.index', ['slug' => 'privacy']) }}" class="btn {{ $slug === 'privacy' ? 'btn-success text-white shadow-sm' : 'btn-outline-secondary' }} btn-sm rounded-pill px-3.5 py-1.5 fw-semibold d-inline-flex align-items-center gap-1.5 text-nowrap">
            <i class="fas fa-user-shield"></i> Privacy Policy
        </a>
        <a href="{{ route('settings.pages-content.index', ['slug' => 'terms']) }}" class="btn {{ $slug === 'terms' ? 'btn-success text-white shadow-sm' : 'btn-outline-secondary' }} btn-sm rounded-pill px-3.5 py-1.5 fw-semibold d-inline-flex align-items-center gap-1.5 text-nowrap">
            <i class="fas fa-scale-balanced"></i> Terms &amp; Conditions
        </a>
        <a href="{{ route('settings.pages-content.index', ['slug' => 'opportunities']) }}" class="btn {{ $slug === 'opportunities' ? 'btn-success text-white shadow-sm' : 'btn-outline-secondary' }} btn-sm rounded-pill px-3.5 py-1.5 fw-semibold d-inline-flex align-items-center gap-1.5 text-nowrap">
            <i class="fas fa-fire"></i> Active Opportunities
        </a>
    </div>

    {{-- ── Flash Notifications ──────────────────────────────────────── --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4 d-flex align-items-center gap-2" role="alert">
            <i class="fas fa-check-circle fs-5 text-success"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-center gap-2 mb-1">
                <i class="fas fa-triangle-exclamation fs-5 text-danger"></i>
                <strong>Please fix the form errors:</strong>
            </div>
            <ul class="mb-0 ps-3 small">
                @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
            </ul>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- ── 2. Form ───────────────────────────────────────────────────── --}}
    <form action="{{ route('settings.pages-content.update') }}" method="POST" enctype="multipart/form-data" id="pageContentForm">
        @csrf
        <input type="hidden" name="slug" value="{{ $slug ?? 'home' }}">

        @if($slug === 'about')
            @include('backend.pages.partials.about')
        @elseif($slug === 'contact')
            @include('backend.pages.partials.contact')
        @elseif($slug === 'privacy' || $slug === 'terms')
            @include('backend.pages.partials.legal')
        @elseif($slug === 'opportunities')
            @include('backend.pages.partials.opportunities')
        @else
            {{-- Tab Navigation for Home Page --}}
            <div class="inv-table-card mb-0 p-0 overflow-hidden mb-4">
                <div class="db-panel-head bg-light border-bottom-0" style="border-radius: 14px 14px 0 0;">
                <ul class="nav tab-pill-nav gap-1 overflow-auto flex-nowrap pb-1 mb-0" id="pageEditorTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#tab-hero" type="button" role="tab" aria-selected="true">
                            <i class="fas fa-crown"></i> Hero Banner
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-opps" type="button" role="tab">
                            <i class="fas fa-boxes-stacked"></i> Opportunities
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-features" type="button" role="tab">
                            <i class="fas fa-bolt"></i> Features Strip
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-how" type="button" role="tab">
                            <i class="fas fa-list-check"></i> How It Works
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-why" type="button" role="tab">
                            <i class="fas fa-shield-heart"></i> Why Invest
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-cta" type="button" role="tab">
                            <i class="fas fa-bullhorn"></i> CTA Banner
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-seo" type="button" role="tab">
                            <i class="fas fa-magnifying-glass"></i> SEO &amp; Meta
                        </button>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Tab Content --}}
        <div class="tab-content" id="pageEditorTabsContent">

            {{-- ═══════════════════════════════════════════════════
                 TAB 1 — HERO BANNER
            ═══════════════════════════════════════════════════ --}}
            <div class="tab-pane fade show active" id="tab-hero" role="tabpanel">
                <div class="row g-4">

                    {{-- LEFT: Headings + Buttons + Badges --}}
                    <div class="col-xl-7 col-lg-6">

                        {{-- Headings & Live Badge Card --}}
                        <div class="inv-table-card mb-4">
                            <div class="db-panel-head bg-light">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fas fa-heading text-primary"></i>
                                    <span class="db-panel-head-title">Main Heading &amp; Live Badge</span>
                                </div>
                            </div>
                            <div class="p-4">
                                <div class="d-flex align-items-center gap-3 mb-3 p-3 bg-light rounded-3 border">
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input" type="checkbox" role="switch" id="hero_badge_toggle"
                                               name="content[hero][show_badge]" value="1"
                                               {{ !empty($content['hero']['show_badge']) ? 'checked' : '' }}>
                                    </div>
                                    <div>
                                        <label class="form-check-label fw-semibold small text-dark" for="hero_badge_toggle">Show Top Live Badge Strip</label>
                                        <p class="extra-small text-muted mb-0">Display the blinking green "LIVE INVESTMENTS ACTIVE" badge on the hero</p>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold small text-dark">Live Badge Text</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-light"><i class="fas fa-circle fa-beat text-success" style="font-size:0.5rem;"></i></span>
                                        <input type="text" class="form-control" name="content[hero][badge_text]"
                                               value="{{ $content['hero']['badge_text'] ?? 'LIVE INVESTMENTS ACTIVE' }}">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold small text-dark">Hero Title <span class="text-muted fw-normal">(use new lines for line breaks)</span></label>
                                    <textarea class="form-control" name="content[hero][title]" rows="3"
                                              placeholder="Invest in Real Products&#10;Earn Weekly Profit">{{ $content['hero']['title'] ?? "Invest in Real Products\nEarn Weekly Profit" }}</textarea>
                                    <small class="text-muted extra-small">Each line becomes a separate line in the heading. Use the field below to bold a phrase in green.</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold small text-dark">Highlighted Phrase <span class="text-muted fw-normal">(rendered in green gradient)</span></label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-success-subtle text-success border-success-subtle"><i class="fas fa-highlighter"></i></span>
                                        <input type="text" class="form-control" name="content[hero][highlight_text]"
                                               value="{{ $content['hero']['highlight_text'] ?? 'Weekly Profit' }}"
                                               placeholder="e.g. Weekly Profit">
                                    </div>
                                </div>

                                <div class="mb-0">
                                    <label class="form-label fw-semibold small text-dark">Hero Sub-Description</label>
                                    <textarea class="form-control" name="content[hero][description]" rows="3"
                                              placeholder="We import high demand products from China.">{{ $content['hero']['description'] ?? "We import high demand products from China.\nYou invest, we handle the rest and you earn\nweekly profit after sales." }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- CTA Buttons Card --}}
                        <div class="inv-table-card mb-4">
                            <div class="db-panel-head bg-light">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fas fa-computer-mouse text-primary"></i>
                                    <span class="db-panel-head-title">Call-To-Action Buttons</span>
                                </div>
                            </div>
                            <div class="p-4">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-dark">Primary Button — Logged In Users</label>
                                        <input type="text" class="form-control form-control-sm" name="content[hero][btn1_auth_text]"
                                               value="{{ $content['hero']['btn1_auth_text'] ?? ($content['hero']['btn1_text'] ?? 'Explore Opportunities') }}"
                                               placeholder="Explore Opportunities">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-dark">Primary Button — Guest / Not Logged In</label>
                                        <input type="text" class="form-control form-control-sm" name="content[hero][btn1_guest_text]"
                                               value="{{ $content['hero']['btn1_guest_text'] ?? 'Sign Up to Invest' }}"
                                               placeholder="Sign Up to Invest">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-dark">Primary Button Link / Anchor</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-light"><i class="fas fa-link text-muted"></i></span>
                                            <input type="text" class="form-control" name="content[hero][btn1_link]"
                                                   value="{{ $content['hero']['btn1_link'] ?? '#opportunities' }}"
                                                   placeholder="#opportunities">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-dark">Secondary Button Text</label>
                                        <input type="text" class="form-control form-control-sm" name="content[hero][btn2_text]"
                                               value="{{ $content['hero']['btn2_text'] ?? 'How It Works' }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-dark">Secondary Button Link / Anchor</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-light"><i class="fas fa-link text-muted"></i></span>
                                            <input type="text" class="form-control" name="content[hero][btn2_link]"
                                                   value="{{ $content['hero']['btn2_link'] ?? '#howItWorks' }}"
                                                   placeholder="#howItWorks">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Trust Badges Repeater --}}
                        <div class="inv-table-card">
                            <div class="db-panel-head bg-light">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fas fa-award text-success"></i>
                                    <span class="db-panel-head-title">Hero Trust Badges (Bottom Strip)</span>
                                </div>
                                <button type="button" class="btn btn-outline-success btn-sm rounded-3 px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-1" onclick="addTrustBadge()">
                                    <i class="fas fa-plus"></i> Add Badge
                                </button>
                            </div>
                            <div class="p-4">
                                <div id="trustBadgesContainer" class="d-flex flex-column gap-3">
                                    @php $trustBadges = $content['hero']['trust_badges'] ?? []; @endphp
                                    @foreach($trustBadges as $idx => $badge)
                                        <div class="repeater-item">
                                            <button type="button" class="btn btn-outline-danger repeater-remove" onclick="removeItem(this)" title="Remove">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            <div class="row g-2 align-items-center pe-4">
                                                <div class="col-md-4">
                                                    <label class="fld-label">Icon Class <span class="fw-normal text-muted">(FontAwesome)</span></label>
                                                    <div class="input-group input-group-sm icon-input-group">
                                                        <span class="input-group-text"><i class="{{ $badge['icon'] ?? 'fas fa-shield-heart' }}"></i></span>
                                                        <input type="text" class="form-control icon-input" name="content[hero][trust_badges][{{ $idx }}][icon]"
                                                               value="{{ $badge['icon'] ?? 'fa-solid fa-shield-heart' }}" onkeyup="syncIcon(this)">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="fld-label">Badge Title</label>
                                                    <input type="text" class="form-control form-control-sm" name="content[hero][trust_badges][{{ $idx }}][title]"
                                                           value="{{ $badge['title'] ?? '' }}" placeholder="e.g. 100% Secure">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="fld-label">Badge Subtitle</label>
                                                    <input type="text" class="form-control form-control-sm" name="content[hero][trust_badges][{{ $idx }}][subtitle]"
                                                           value="{{ $badge['subtitle'] ?? '' }}" placeholder="e.g. Your investment is safe">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <p class="extra-small text-muted mt-2 mb-0"><i class="fas fa-circle-info me-1 text-primary"></i> Use FontAwesome class names like <code>fas fa-shield-heart</code>, <code>fas fa-clock</code>, etc.</p>
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT: Media + Floating Stat Cards --}}
                    <div class="col-xl-5 col-lg-6">

                        {{-- Hero Media Card --}}
                        <div class="inv-table-card mb-4">
                            <div class="db-panel-head bg-light">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fas fa-photo-film text-primary"></i>
                                    <span class="db-panel-head-title">Hero Visual Media</span>
                                </div>
                            </div>
                            <div class="p-4">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold small text-dark">Display Type</label>
                                    <select class="form-select form-select-sm" name="content[hero][media_type]" id="heroMediaType" onchange="toggleMediaType(this.value)">
                                        <option value="image" {{ ($content['hero']['media_type'] ?? 'image') === 'image' ? 'selected' : '' }}>Upload Hero Image</option>
                                        <option value="video" {{ ($content['hero']['media_type'] ?? '') === 'video' ? 'selected' : '' }}>Embedded Video (YouTube / Vimeo)</option>
                                    </select>
                                </div>

                                {{-- Image Block --}}
                                <div id="mediaImageBlock" class="{{ ($content['hero']['media_type'] ?? 'image') === 'video' ? 'd-none' : '' }}">
                                    @php $heroImg = $content['hero']['image'] ?? 'images/hero.jpg'; @endphp
                                    <img src="{{ asset($heroImg) }}" id="heroImgPreview" alt="Hero Preview"
                                         class="hero-img-preview mb-2"
                                         onerror="this.src='{{ asset('images/hero.jpg') }}'">
                                    <input type="file" class="form-control form-control-sm mb-1" name="hero_image"
                                           accept="image/*" onchange="previewImg(this,'heroImgPreview')">
                                    <input type="hidden" name="content[hero][image]" value="{{ $heroImg }}">
                                    <small class="text-muted extra-small">Recommended: 1200×800px · JPG / PNG / WEBP · Max 4MB</small>
                                </div>

                                {{-- Video Block --}}
                                <div id="mediaVideoBlock" class="{{ ($content['hero']['media_type'] ?? 'image') === 'video' ? '' : 'd-none' }}">
                                    <label class="form-label fw-semibold small text-dark">YouTube / Vimeo Video URL (or Embed URL)</label>
                                    <input type="text" class="form-control form-control-sm mb-1" name="content[hero][video_url]"
                                           value="{{ $content['hero']['video_url'] ?? '' }}"
                                           placeholder="https://www.youtube.com/watch?v=... or https://youtube.com/shorts/... or https://vimeo.com/...">
                                    <small class="text-muted extra-small">Supports standard YouTube links, <strong>YouTube Shorts</strong>, youtu.be, Vimeo, or direct embed URLs.</small>
                                </div>
                            </div>
                        </div>

                        {{-- Floating Stat Cards --}}
                        <div class="inv-table-card">
                            <div class="db-panel-head bg-light">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fas fa-chart-simple text-success"></i>
                                    <span class="db-panel-head-title">Floating Stat Cards (Over Hero Visual)</span>
                                </div>
                            </div>
                            <div class="p-4">
                                {{-- Card 1 --}}
                                <div class="p-3 border rounded-3 bg-light mb-3">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <span class="badge bg-primary rounded-pill extra-small">Card 1 — Left Side</span>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-12">
                                            <label class="fld-label">Icon Class</label>
                                            <div class="input-group input-group-sm icon-input-group">
                                                <span class="input-group-text"><i class="{{ $content['hero']['stat_card_1_icon'] ?? 'fas fa-arrow-trend-up' }}"></i></span>
                                                <input type="text" class="form-control icon-input" name="content[hero][stat_card_1_icon]"
                                                       value="{{ $content['hero']['stat_card_1_icon'] ?? 'fas fa-arrow-trend-up' }}" onkeyup="syncIcon(this)">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <label class="fld-label">Label</label>
                                            <input type="text" class="form-control form-control-sm" name="content[hero][stat_card_1_label]"
                                                   value="{{ $content['hero']['stat_card_1_label'] ?? 'Total Investors' }}">
                                        </div>
                                        <div class="col-6">
                                            <label class="fld-label">Value</label>
                                            <input type="text" class="form-control form-control-sm" name="content[hero][stat_card_1_value]"
                                                   value="{{ $content['hero']['stat_card_1_value'] ?? '1,240+' }}">
                                        </div>
                                    </div>
                                </div>

                                {{-- Card 2 --}}
                                <div class="p-3 border rounded-3 bg-light">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <span class="badge bg-success rounded-pill extra-small">Card 2 — Right Side</span>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-12">
                                            <label class="fld-label">Icon Class</label>
                                            <div class="input-group input-group-sm icon-input-group">
                                                <span class="input-group-text"><i class="{{ $content['hero']['stat_card_2_icon'] ?? 'fa-solid fa-shield-heart' }}"></i></span>
                                                <input type="text" class="form-control icon-input" name="content[hero][stat_card_2_icon]"
                                                       value="{{ $content['hero']['stat_card_2_icon'] ?? 'fa-solid fa-shield-heart' }}" onkeyup="syncIcon(this)">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <label class="fld-label">Label</label>
                                            <input type="text" class="form-control form-control-sm" name="content[hero][stat_card_2_label]"
                                                   value="{{ $content['hero']['stat_card_2_label'] ?? 'Secured Investments' }}">
                                        </div>
                                        <div class="col-6">
                                            <label class="fld-label">Value</label>
                                            <input type="text" class="form-control form-control-sm" name="content[hero][stat_card_2_value]"
                                                   value="{{ $content['hero']['stat_card_2_value'] ?? '100%' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>{{-- end hero tab --}}

            {{-- ═══════════════════════════════════════════════════
                 TAB 2 — OPPORTUNITIES SECTION
            ═══════════════════════════════════════════════════ --}}
            <div class="tab-pane fade" id="tab-opps" role="tabpanel">
                <div class="inv-table-card" style="max-width:800px;">
                    <div class="db-panel-head bg-light">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-boxes-stacked text-primary"></i>
                            <span class="db-panel-head-title">Opportunities Section Labels</span>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="alert alert-info border-0 rounded-3 d-flex gap-2 small mb-4" role="alert">
                            <i class="fas fa-circle-info mt-0.5"></i>
                            <div>The investment cards shown in this section are pulled automatically from the <strong>Posts</strong> management page. Only the section title, link text, and empty state message are editable here.</div>
                        </div>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold small text-dark">Section Heading Title</label>
                                <input type="text" class="form-control" name="content[opportunities][title]"
                                       value="{{ $content['opportunities']['title'] ?? 'Active Investment Opportunities' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-dark">Quick Link Text ("How It Works")</label>
                                <input type="text" class="form-control form-control-sm" name="content[opportunities][how_it_works_text]"
                                       value="{{ $content['opportunities']['how_it_works_text'] ?? 'How It Works' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-dark">Empty State Message</label>
                                <input type="text" class="form-control form-control-sm" name="content[opportunities][empty_message]"
                                       value="{{ $content['opportunities']['empty_message'] ?? 'No active investment opportunities right now. Check back soon!' }}"
                                       placeholder="Shown when no active posts exist">
                            </div>
                        </div>
                    </div>
                </div>
            </div>{{-- end opps tab --}}

            {{-- ═══════════════════════════════════════════════════
                 TAB 3 — FEATURES STRIP
            ═══════════════════════════════════════════════════ --}}
            <div class="tab-pane fade" id="tab-features" role="tabpanel">
                <div class="inv-table-card">
                    <div class="db-panel-head bg-light">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-bolt text-warning"></i>
                            <span class="db-panel-head-title">Features Highlight Strip</span>
                        </div>
                        <button type="button" class="btn btn-outline-success btn-sm rounded-3 px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-1" onclick="addFeatureItem()">
                            <i class="fas fa-plus"></i> Add Feature
                        </button>
                    </div>
                    <div class="p-4">
                        <div class="section-toggle-bar mb-4">
                            <div>
                                <strong>Show Features Strip Section</strong>
                                <p class="small text-muted mb-0">Display the icon highlight strip beneath the investments listing</p>
                            </div>
                            <div class="form-check form-switch mb-0">
                                <input type="hidden" name="content[features][show_section]" value="0">
                                <input class="form-check-input" type="checkbox" role="switch"
                                       name="content[features][show_section]" value="1"
                                       {{ !empty($content['features']['show_section']) ? 'checked' : '' }}>
                            </div>
                        </div>

                        <div id="featuresContainer" class="row g-3">
                            @php $featureItems = $content['features']['items'] ?? []; @endphp
                            @foreach($featureItems as $idx => $feat)
                                <div class="col-md-6 feature-row">
                                    <div class="repeater-item h-100">
                                        <button type="button" class="btn btn-outline-danger repeater-remove" onclick="removeItem(this)" title="Remove">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <div class="pe-4">
                                            <div class="mb-2">
                                                <label class="fld-label">Icon Class</label>
                                                <div class="input-group input-group-sm icon-input-group">
                                                    <span class="input-group-text"><i class="{{ $feat['icon'] ?? 'fas fa-star' }}"></i></span>
                                                    <input type="text" class="form-control icon-input" name="content[features][items][{{ $idx }}][icon]"
                                                           value="{{ $feat['icon'] ?? 'fas fa-star' }}" onkeyup="syncIcon(this)">
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="fld-label">Title</label>
                                                <input type="text" class="form-control form-control-sm" name="content[features][items][{{ $idx }}][title]"
                                                       value="{{ $feat['title'] ?? '' }}" placeholder="e.g. Safe & Secure">
                                            </div>
                                            <div>
                                                <label class="fld-label">Description</label>
                                                <textarea class="form-control form-control-sm" name="content[features][items][{{ $idx }}][desc]" rows="2">{{ $feat['desc'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>{{-- end features tab --}}

            {{-- ═══════════════════════════════════════════════════
                 TAB 4 — HOW IT WORKS (STEPS)
            ═══════════════════════════════════════════════════ --}}
            <div class="tab-pane fade" id="tab-how" role="tabpanel">
                <div class="inv-table-card">
                    <div class="db-panel-head bg-light">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-list-check text-primary"></i>
                            <span class="db-panel-head-title">How It Works — Process Steps</span>
                        </div>
                        <button type="button" class="btn btn-outline-success btn-sm rounded-3 px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-1" onclick="addHowStep()">
                            <i class="fas fa-plus"></i> Add Step
                        </button>
                    </div>
                    <div class="p-4">
                        <div class="section-toggle-bar mb-4">
                            <div>
                                <strong>Show "How It Works" Section</strong>
                                <p class="small text-muted mb-0">Display the numbered step-by-step process on the home page</p>
                            </div>
                            <div class="form-check form-switch mb-0">
                                <input type="hidden" name="content[how_it_works][show_section]" value="0">
                                <input class="form-check-input" type="checkbox" role="switch"
                                       name="content[how_it_works][show_section]" value="1"
                                       {{ !empty($content['how_it_works']['show_section']) ? 'checked' : '' }}>
                            </div>
                        </div>

                        {{-- Section Labels --}}
                        <div class="row g-3 mb-4 pb-4 border-bottom">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-dark">Small Badge / Label</label>
                                <input type="text" class="form-control form-control-sm" name="content[how_it_works][section_label]"
                                       value="{{ $content['how_it_works']['section_label'] ?? 'Step by Step' }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-dark">Section Main Title</label>
                                <input type="text" class="form-control form-control-sm" name="content[how_it_works][title]"
                                       value="{{ $content['how_it_works']['title'] ?? 'How It Works' }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-dark">Section Subtitle</label>
                                <input type="text" class="form-control form-control-sm" name="content[how_it_works][subtitle]"
                                       value="{{ $content['how_it_works']['subtitle'] ?? 'A transparent 6-step process from product post to profit' }}"
                                       placeholder="Short description shown below title">
                            </div>
                        </div>

                        {{-- Steps Repeater --}}
                        <div id="howStepsContainer" class="row g-3">
                            @php $steps = $content['how_it_works']['steps'] ?? []; @endphp
                            @foreach($steps as $idx => $step)
                                <div class="col-lg-4 col-md-6 step-row">
                                    <div class="repeater-item h-100">
                                        <button type="button" class="btn btn-outline-danger repeater-remove" onclick="removeItem(this)" title="Remove">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <div class="pe-4">
                                            <div class="row g-2 mb-2">
                                                <div class="col-4">
                                                    <label class="fld-label">Step #</label>
                                                    <input type="text" class="form-control form-control-sm text-center fw-bold"
                                                           name="content[how_it_works][steps][{{ $idx }}][num]"
                                                           value="{{ $step['num'] ?? sprintf('%02d', $idx + 1) }}">
                                                </div>
                                                <div class="col-8">
                                                    <label class="fld-label">Icon Class</label>
                                                    <div class="input-group input-group-sm icon-input-group">
                                                        <span class="input-group-text"><i class="{{ isset($step['icon']) ? 'fas ' . $step['icon'] : 'fas fa-check' }}"></i></span>
                                                        <input type="text" class="form-control icon-input"
                                                               name="content[how_it_works][steps][{{ $idx }}][icon]"
                                                               value="{{ $step['icon'] ?? 'fa-boxes-packing' }}" onkeyup="syncIconFas(this)">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="fld-label">Step Title</label>
                                                <input type="text" class="form-control form-control-sm fw-semibold"
                                                       name="content[how_it_works][steps][{{ $idx }}][title]"
                                                       value="{{ $step['title'] ?? '' }}" placeholder="e.g. Product Posted">
                                            </div>
                                            <div>
                                                <label class="fld-label">Description</label>
                                                <textarea class="form-control form-control-sm"
                                                          name="content[how_it_works][steps][{{ $idx }}][desc]" rows="2">{{ $step['desc'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>{{-- end how tab --}}

            {{-- ═══════════════════════════════════════════════════
                 TAB 5 — WHY INVEST WITH US
            ═══════════════════════════════════════════════════ --}}
            <div class="tab-pane fade" id="tab-why" role="tabpanel">
                <div class="inv-table-card">
                    <div class="db-panel-head" style="background:#1e293b;">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-shield-heart text-success"></i>
                            <span class="db-panel-head-title text-white">Why Invest With Us — Benefit Cards</span>
                        </div>
                        <button type="button" class="btn btn-outline-light btn-sm rounded-3 px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-1" onclick="addWhyCard()">
                            <i class="fas fa-plus"></i> Add Card
                        </button>
                    </div>
                    <div class="p-4">
                        <div class="section-toggle-bar mb-4">
                            <div>
                                <strong>Show "Why Invest With Us" Section</strong>
                                <p class="small text-muted mb-0">Display the dark-themed benefit card grid on the home page</p>
                            </div>
                            <div class="form-check form-switch mb-0">
                                <input type="hidden" name="content[why_invest][show_section]" value="0">
                                <input class="form-check-input" type="checkbox" role="switch"
                                       name="content[why_invest][show_section]" value="1"
                                       {{ !empty($content['why_invest']['show_section']) ? 'checked' : '' }}>
                            </div>
                        </div>

                        <div class="row g-3 mb-4 pb-4 border-bottom">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-dark">Section Title</label>
                                <input type="text" class="form-control" name="content[why_invest][title]"
                                       value="{{ $content['why_invest']['title'] ?? 'Why Invest With Us?' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-dark">Section Subtitle</label>
                                <input type="text" class="form-control" name="content[why_invest][subtitle]"
                                       value="{{ $content['why_invest']['subtitle'] ?? 'Built for security, transparency, and consistent weekly returns' }}">
                            </div>
                        </div>

                        {{-- Dark Cards Repeater --}}
                        <div id="whyCardsContainer" class="row g-3">
                            @php $whyCards = $content['why_invest']['cards'] ?? []; @endphp
                            @foreach($whyCards as $idx => $card)
                                <div class="col-md-6 col-lg-3 why-card-row">
                                    <div class="dark-repeater-item h-100">
                                        <button type="button" class="btn btn-outline-danger repeater-remove" onclick="removeItem(this)" title="Remove" style="background:rgba(239,68,68,0.1);">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <div class="pe-4">
                                            <div class="row g-2 mb-2">
                                                <div class="col-8">
                                                    <label class="fld-label-light">Icon Class</label>
                                                    <div class="input-group input-group-sm icon-input-group">
                                                        <span class="input-group-text bg-dark border-secondary">
                                                            <i class="{{ $card['icon'] ?? 'fa-shield-halved' }}" style="color:{{ $card['color'] ?? '#38BDF8' }};"></i>
                                                        </span>
                                                        <input type="text" class="form-control form-control-sm bg-dark text-white border-secondary icon-input"
                                                               name="content[why_invest][cards][{{ $idx }}][icon]"
                                                               value="{{ $card['icon'] ?? 'fa-shield-halved' }}" onkeyup="syncIcon(this)">
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <label class="fld-label-light">Icon Color</label>
                                                    <input type="color" class="form-control form-control-sm form-control-color w-100 bg-dark border-secondary"
                                                           name="content[why_invest][cards][{{ $idx }}][color]"
                                                           value="{{ $card['color'] ?? '#38BDF8' }}">
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="fld-label-light">Card Title</label>
                                                <input type="text" class="form-control form-control-sm bg-dark text-white border-secondary fw-semibold"
                                                       name="content[why_invest][cards][{{ $idx }}][title]"
                                                       value="{{ $card['title'] ?? '' }}" placeholder="Card title">
                                            </div>
                                            <div>
                                                <label class="fld-label-light">Description</label>
                                                <textarea class="form-control form-control-sm bg-dark text-white border-secondary"
                                                          name="content[why_invest][cards][{{ $idx }}][desc]" rows="3">{{ $card['desc'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>{{-- end why tab --}}

            {{-- ═══════════════════════════════════════════════════
                 TAB 6 — CTA BANNER
            ═══════════════════════════════════════════════════ --}}
            <div class="tab-pane fade" id="tab-cta" role="tabpanel">
                <div class="inv-table-card" style="max-width:800px;">
                    <div class="db-panel-head bg-light">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-bullhorn text-primary"></i>
                            <span class="db-panel-head-title">Call-To-Action (CTA) Banner</span>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="section-toggle-bar mb-4">
                            <div>
                                <strong>Show CTA Banner Section</strong>
                                <p class="small text-muted mb-0">Display an optional green-gradient action banner before the footer</p>
                            </div>
                            <div class="form-check form-switch mb-0">
                                <input type="hidden" name="content[cta][show_section]" value="0">
                                <input class="form-check-input" type="checkbox" role="switch"
                                       name="content[cta][show_section]" value="1"
                                       {{ !empty($content['cta']['show_section']) ? 'checked' : '' }}>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold small text-dark">Banner Heading</label>
                                <input type="text" class="form-control" name="content[cta][title]"
                                       value="{{ $content['cta']['title'] ?? 'Ready to Start Your Investment Journey?' }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold small text-dark">Banner Subtitle</label>
                                <textarea class="form-control form-control-sm" name="content[cta][subtitle]" rows="2">{{ $content['cta']['subtitle'] ?? 'Join our verified investors community and earn reliable weekly returns.' }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-dark">Button Text</label>
                                <input type="text" class="form-control form-control-sm" name="content[cta][btn_text]"
                                       value="{{ $content['cta']['btn_text'] ?? 'Create Free Account' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-dark">Button Link URL</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light"><i class="fas fa-link text-muted"></i></span>
                                    <input type="text" class="form-control" name="content[cta][btn_link]"
                                           value="{{ $content['cta']['btn_link'] ?? '/register' }}" placeholder="/register">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>{{-- end cta tab --}}

            {{-- ═══════════════════════════════════════════════════
                 TAB 7 — SEO & META
            ═══════════════════════════════════════════════════ --}}
            <div class="tab-pane fade" id="tab-seo" role="tabpanel">
                <div class="inv-table-card" style="max-width:800px;">
                    <div class="db-panel-head bg-light">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-magnifying-glass text-primary"></i>
                            <span class="db-panel-head-title">Search Engine Optimization (SEO &amp; Meta)</span>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="alert alert-warning border-0 rounded-3 d-flex gap-2 small mb-4" role="alert">
                            <i class="fas fa-triangle-exclamation mt-0.5 flex-shrink-0"></i>
                            <div>These fields affect how your home page appears in search engine results (Google, Bing, etc.) and link previews. Leave empty to fall back to the company defaults from System Settings.</div>
                        </div>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold small text-dark">Meta Page Title</label>
                                <input type="text" class="form-control" name="content[seo][meta_title]"
                                       value="{{ $content['seo']['meta_title'] ?? '' }}"
                                       placeholder="e.g. Invest in Real Products — Earn Weekly Profit Returns">
                                <small class="text-muted extra-small">Recommended length: 50–60 characters. Leave blank to use company default.</small>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold small text-dark">Meta Description</label>
                                <textarea class="form-control" name="content[seo][meta_description]" rows="3"
                                          placeholder="A brief, compelling summary of your investment platform for search engines...">{{ $content['seo']['meta_description'] ?? '' }}</textarea>
                                <small class="text-muted extra-small">Recommended length: 150–160 characters. Leave blank to use company default.</small>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold small text-dark">Meta Keywords <span class="text-muted fw-normal">(comma separated)</span></label>
                                <input type="text" class="form-control form-control-sm" name="content[seo][meta_keywords]"
                                       value="{{ $content['seo']['meta_keywords'] ?? '' }}"
                                       placeholder="e.g. investment, weekly profit, import business, smart returns">
                            </div>
                        </div>
                    </div>
                </div>
            </div>{{-- end seo tab --}}

        </div>{{-- end tab-content --}}
        @endif

        {{-- ── Sticky Save Bar ──────────────────────────────────────────── --}}
        <div class="sticky-save-bar">
            <div class="inner">
                <div class="d-flex align-items-center gap-2 text-muted small">
                    <i class="fas fa-circle-info text-primary flex-shrink-0"></i>
                    <span>All changes take effect <strong>immediately</strong> on the live {{ strtolower($currentTitle) }} after saving.</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ $liveUrl }}" target="_blank" class="btn btn-outline-secondary btn-sm rounded-3 px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-2">
                        <i class="fas fa-eye"></i> <span class="d-none d-sm-inline">Preview</span>
                    </a>
                    <button type="submit" class="btn btn-primary btn-sm px-4 py-2 rounded-3 fw-semibold shadow-sm d-inline-flex align-items-center gap-2">
                        <i class="fas fa-save"></i>
                        <span>Save {{ $currentTitle }}</span>
                    </button>
                </div>
            </div>
        </div>

    </form>
</div>

{{-- ── Reset Confirmation Modal ────────────────────────────────── --}}
<div class="modal fade" id="resetModal" tabindex="-1" aria-labelledby="resetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-triangle-exclamation"></i>
                    <h5 class="modal-title fs-6 fw-bold mb-0" id="resetModalLabel">Reset {{ $currentTitle }} to Defaults?</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted mb-0">This will overwrite <strong>all</strong> {{ strtolower($currentTitle) }} content with factory default values. Your custom changes will be permanently reset.</p>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary btn-sm rounded-3 px-3" data-bs-dismiss="modal">
                    <i class="fas fa-arrow-left me-1"></i> Cancel
                </button>
                <form action="{{ route('settings.pages-content.reset') }}" method="POST">
                    @csrf
                    <input type="hidden" name="slug" value="{{ $slug ?? 'home' }}">
                    <button type="submit" class="btn btn-danger btn-sm rounded-3 px-4 fw-bold">
                        <i class="fas fa-rotate-left me-1"></i> Yes, Reset Defaults
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // ── Preview uploaded image ───────────────────────────────────────
    function previewImg(input, previewId) {
        if (!input.files || !input.files[0]) return;
        const reader = new FileReader();
        reader.onload = e => document.getElementById(previewId).src = e.target.result;
        reader.readAsDataURL(input.files[0]);
    }

    // ── Toggle image / video block ───────────────────────────────────
    function toggleMediaType(val) {
        document.getElementById('mediaImageBlock').classList.toggle('d-none', val === 'video');
        document.getElementById('mediaVideoBlock').classList.toggle('d-none', val !== 'video');
    }

    // ── Sync FA icon preview with typed class ────────────────────────
    function syncIcon(input) {
        const ico = input.parentElement.querySelector('i');
        if (ico) ico.className = input.value.trim() || 'fas fa-question';
    }
    // For steps where only the suffix is stored (e.g. fa-boxes-packing)
    function syncIconFas(input) {
        const ico = input.parentElement.querySelector('i');
        if (ico) ico.className = 'fas ' + (input.value.trim() || 'fa-question');
    }

    // ── Remove any repeater card ─────────────────────────────────────
    function removeItem(btn) {
        if (!confirm('Remove this item?')) return;
        btn.closest('.repeater-item, .dark-repeater-item')?.parentElement?.remove();
    }

    // ── Add Trust Badge ──────────────────────────────────────────────
    function addTrustBadge() {
        const c = document.getElementById('trustBadgesContainer');
        const idx = Date.now();
        c.insertAdjacentHTML('beforeend', `
        <div class="repeater-item">
            <button type="button" class="btn btn-outline-danger repeater-remove" onclick="removeItem(this)" title="Remove"><i class="fas fa-times"></i></button>
            <div class="row g-2 align-items-center pe-4">
                <div class="col-md-4">
                    <label class="fld-label">Icon Class</label>
                    <div class="input-group input-group-sm icon-input-group">
                        <span class="input-group-text"><i class="fas fa-shield-heart"></i></span>
                        <input type="text" class="form-control icon-input" name="content[hero][trust_badges][${idx}][icon]" value="fas fa-shield-heart" onkeyup="syncIcon(this)">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="fld-label">Badge Title</label>
                    <input type="text" class="form-control form-control-sm" name="content[hero][trust_badges][${idx}][title]" placeholder="e.g. 100% Secure">
                </div>
                <div class="col-md-4">
                    <label class="fld-label">Badge Subtitle</label>
                    <input type="text" class="form-control form-control-sm" name="content[hero][trust_badges][${idx}][subtitle]" placeholder="e.g. Your investment is safe">
                </div>
            </div>
        </div>`);
    }

    // ── Add Feature Item ─────────────────────────────────────────────
    function addFeatureItem() {
        const c = document.getElementById('featuresContainer');
        const idx = Date.now();
        c.insertAdjacentHTML('beforeend', `
        <div class="col-md-6 feature-row">
            <div class="repeater-item h-100">
                <button type="button" class="btn btn-outline-danger repeater-remove" onclick="removeItem(this)" title="Remove"><i class="fas fa-times"></i></button>
                <div class="pe-4">
                    <div class="mb-2">
                        <label class="fld-label">Icon Class</label>
                        <div class="input-group input-group-sm icon-input-group">
                            <span class="input-group-text"><i class="fas fa-star"></i></span>
                            <input type="text" class="form-control icon-input" name="content[features][items][${idx}][icon]" value="fas fa-star" onkeyup="syncIcon(this)">
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="fld-label">Title</label>
                        <input type="text" class="form-control form-control-sm" name="content[features][items][${idx}][title]" placeholder="Feature title">
                    </div>
                    <div>
                        <label class="fld-label">Description</label>
                        <textarea class="form-control form-control-sm" name="content[features][items][${idx}][desc]" rows="2" placeholder="Brief description..."></textarea>
                    </div>
                </div>
            </div>
        </div>`);
    }

    // ── Add How It Works Step ────────────────────────────────────────
    function addHowStep() {
        const c   = document.getElementById('howStepsContainer');
        const cnt = c.querySelectorAll('.step-row').length + 1;
        const num = cnt < 10 ? '0' + cnt : cnt;
        const idx = Date.now();
        c.insertAdjacentHTML('beforeend', `
        <div class="col-lg-4 col-md-6 step-row">
            <div class="repeater-item h-100">
                <button type="button" class="btn btn-outline-danger repeater-remove" onclick="removeItem(this)" title="Remove"><i class="fas fa-times"></i></button>
                <div class="pe-4">
                    <div class="row g-2 mb-2">
                        <div class="col-4">
                            <label class="fld-label">Step #</label>
                            <input type="text" class="form-control form-control-sm text-center fw-bold" name="content[how_it_works][steps][${idx}][num]" value="${num}">
                        </div>
                        <div class="col-8">
                            <label class="fld-label">Icon Class</label>
                            <div class="input-group input-group-sm icon-input-group">
                                <span class="input-group-text"><i class="fas fa-circle-check"></i></span>
                                <input type="text" class="form-control icon-input" name="content[how_it_works][steps][${idx}][icon]" value="fa-circle-check" onkeyup="syncIconFas(this)">
                            </div>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="fld-label">Step Title</label>
                        <input type="text" class="form-control form-control-sm fw-semibold" name="content[how_it_works][steps][${idx}][title]" placeholder="Step title">
                    </div>
                    <div>
                        <label class="fld-label">Description</label>
                        <textarea class="form-control form-control-sm" name="content[how_it_works][steps][${idx}][desc]" rows="2" placeholder="Step description..."></textarea>
                    </div>
                </div>
            </div>
        </div>`);
    }

    // ── Add Why Invest Card ──────────────────────────────────────────
    function addWhyCard() {
        const c   = document.getElementById('whyCardsContainer');
        const idx = Date.now();
        c.insertAdjacentHTML('beforeend', `
        <div class="col-md-6 col-lg-3 why-card-row">
            <div class="dark-repeater-item h-100">
                <button type="button" class="btn btn-outline-danger repeater-remove" onclick="removeItem(this)" title="Remove" style="background:rgba(239,68,68,0.1);"><i class="fas fa-times"></i></button>
                <div class="pe-4">
                    <div class="row g-2 mb-2">
                        <div class="col-8">
                            <label class="fld-label-light">Icon Class</label>
                            <div class="input-group input-group-sm icon-input-group">
                                <span class="input-group-text bg-dark border-secondary"><i class="fas fa-shield-halved" style="color:#38BDF8;"></i></span>
                                <input type="text" class="form-control form-control-sm bg-dark text-white border-secondary icon-input" name="content[why_invest][cards][${idx}][icon]" value="fa-shield-halved" onkeyup="syncIcon(this)">
                            </div>
                        </div>
                        <div class="col-4">
                            <label class="fld-label-light">Color</label>
                            <input type="color" class="form-control form-control-sm form-control-color w-100 bg-dark border-secondary" name="content[why_invest][cards][${idx}][color]" value="#38BDF8">
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="fld-label-light">Card Title</label>
                        <input type="text" class="form-control form-control-sm bg-dark text-white border-secondary fw-semibold" name="content[why_invest][cards][${idx}][title]" placeholder="Card title">
                    </div>
                    <div>
                        <label class="fld-label-light">Description</label>
                        <textarea class="form-control form-control-sm bg-dark text-white border-secondary" name="content[why_invest][cards][${idx}][desc]" rows="3" placeholder="Card description..."></textarea>
                    </div>
                </div>
            </div>
        </div>`);
    }
</script>
@endpush

</x-backend-layout>
