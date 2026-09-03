@php
  $setting = $setting ?? \App\Models\Setting::first();
  $companyName = $setting->company_name ?? 'InvestHub';
  $brandLogo = $setting->primary_logo_url ?? null;
  $user = Auth::user();
@endphp

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg sticky-top bg-white border-bottom shadow-sm" id="mainNavbar">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}" id="navBrand">
      @if($brandLogo)
        <span class="brand-logo-inline">
          <img src="{{ asset($brandLogo) }}" alt="{{ $companyName }}" class="brand-logo-image" style="max-height: 38px; width: auto; object-fit: contain;" />
        </span>
      @else
        <div class="d-flex align-items-center justify-content-center bg-success text-white rounded-3 shadow-sm" style="width:36px; height:36px;">
          <i class="fas fa-chart-line fa-sm"></i>
        </div>
        <span class="brand-text fw-extrabold text-dark fs-5" style="letter-spacing: -0.4px;">{{ $companyName }}</span>
      @endif
    </a>

    <!-- Hamburger menu triggering Offcanvas Sidecanvas -->
    <button class="navbar-toggler border-0 p-2 text-dark d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#frontendMobileOffcanvas" aria-controls="frontendMobileOffcanvas" aria-label="Toggle navigation">
      <i class="fas fa-bars fa-lg"></i>
    </button>

    <!-- Desktop Navbar Links -->
    <div class="collapse navbar-collapse d-none d-lg-flex" id="navContent">
      <ul class="navbar-nav mx-auto gap-2">
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('home') && !request()->has('page') ? 'active' : '' }}" id="nav-how" href="{{ route('home') }}#howItWorks">How It Works</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="nav-opp" href="{{ route('home') }}#opportunities">Active Opportunities</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="nav-about" href="{{ route('home') }}#features">About Us</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="nav-contact" href="#footer-contact">Contact</a>
        </li>
      </ul>

      <div class="d-flex align-items-center gap-2">
        @if (Route::has('login'))
          @auth
            <a href="{{ route('dashboard') }}" class="btn btn-outline-success btn-sm rounded-pill px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-2">
              <i class="fas fa-gauge-high"></i> Dashboard
            </a>
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
              @csrf
              <button type="submit" class="btn btn-light btn-sm rounded-pill px-3 py-1.5 text-muted fw-semibold border">
                <i class="fas fa-right-from-bracket me-1"></i> Log Out
              </button>
            </form>
          @else
            <a href="{{ route('login') }}" class="btn btn-login px-3 py-1.5 fw-semibold" id="loginBtn">Login</a>
            @if (Route::has('register'))
              <a href="{{ route('register') }}" class="btn btn-signup rounded-pill px-4 py-1.5 fw-bold" id="signUpBtn">Sign Up</a>
            @endif
          @endauth
        @endif
      </div>
    </div>
  </div>
</nav>

<!-- FRONTEND MOBILE SIDECANVAS -->
<div class="offcanvas offcanvas-start border-0 shadow-lg" tabindex="-1" id="frontendMobileOffcanvas" aria-labelledby="frontendMobileOffcanvasLabel" style="width: 285px;">
  <div class="offcanvas-header bg-white border-bottom py-3 px-3">
    <div class="d-flex align-items-center gap-2">
      @if($brandLogo)
        <img src="{{ asset($brandLogo) }}" alt="{{ $companyName }}" style="max-height: 32px; width: auto;" />
      @else
        <div class="d-flex align-items-center justify-content-center bg-success text-white rounded-3" style="width:32px; height:32px;">
          <i class="fas fa-chart-line extra-small"></i>
        </div>
        <strong class="text-dark fs-6">{{ $companyName }}</strong>
      @endif
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>

  <div class="offcanvas-body p-3 d-flex flex-column justify-content-between">
    <div class="nav flex-column gap-1">
      <a href="{{ route('home') }}" class="nav-link py-2.5 px-3 rounded-3 text-dark fw-semibold hover-bg-light">
        <i class="fas fa-home me-2 text-success"></i> Home
      </a>
      <a href="{{ route('home') }}#howItWorks" class="nav-link py-2.5 px-3 rounded-3 text-dark fw-semibold hover-bg-light">
        <i class="fas fa-circle-info me-2 text-success"></i> How It Works
      </a>
      <a href="{{ route('home') }}#opportunities" class="nav-link py-2.5 px-3 rounded-3 text-dark fw-semibold hover-bg-light">
        <i class="fas fa-fire me-2 text-success"></i> Active Opportunities
      </a>
      <a href="{{ route('home') }}#features" class="nav-link py-2.5 px-3 rounded-3 text-dark fw-semibold hover-bg-light">
        <i class="fas fa-shield-check me-2 text-success"></i> About Us
      </a>
      <a href="#footer-contact" class="nav-link py-2.5 px-3 rounded-3 text-dark fw-semibold hover-bg-light">
        <i class="fas fa-envelope me-2 text-success"></i> Contact Us
      </a>
    </div>

    <div class="pt-3 border-top">
      @if (Route::has('login'))
        @auth
          <div class="p-2 bg-light rounded-3 mb-2 border d-flex align-items-center gap-2">
            <div class="rounded-circle bg-success text-white fw-bold d-flex align-items-center justify-content-center" style="width:34px; height:34px; font-size:0.75rem;">
              {{ strtoupper(substr($user->name ?? 'User', 0, 2)) }}
            </div>
            <div class="overflow-hidden">
              <strong class="d-block text-dark text-truncate extra-small fw-bold">{{ $user->name ?? 'User' }}</strong>
              <small class="text-muted text-truncate d-block extra-small">{{ $user->email ?? '' }}</small>
            </div>
          </div>
          <a href="{{ route('dashboard') }}" class="btn btn-success w-100 py-2 fw-bold rounded-3 mb-2">
            <i class="fas fa-gauge-high me-1.5"></i> Go to Dashboard
          </a>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-danger w-100 py-2 fw-semibold rounded-3">
              <i class="fas fa-right-from-bracket me-1.5"></i> Log Out
            </button>
          </form>
        @else
          <a href="{{ route('login') }}" class="btn btn-outline-success w-100 py-2 fw-bold rounded-3 mb-2">
            <i class="fas fa-user me-1.5"></i> Login
          </a>
          @if (Route::has('register'))
            <a href="{{ route('register') }}" class="btn btn-success w-100 py-2 fw-bold rounded-3">
              <i class="fas fa-user-plus me-1.5"></i> Sign Up
            </a>
          @endif
        @endauth
      @endif
    </div>
  </div>
</div>
