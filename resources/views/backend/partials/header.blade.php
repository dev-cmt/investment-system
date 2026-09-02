@php
    $userRoles = $user ? $user->getRoleNames()->implode(', ') : 'User';
    $roleName = $userRoles ?: ($isAdmin ? 'Admin' : 'Investor');
@endphp
<!-- ── TOP NAV ─────────────────────────────────────────── -->
<nav id="app-nav">
    <button class="nav-hamburger d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#backendMobileOffcanvas" aria-controls="backendMobileOffcanvas" aria-label="Toggle navigation">
        <i class="fas fa-bars"></i>
    </button>

    <a href="{{ route('dashboard') }}" class="nav-brand">
        @if($setting->primary_logo_url ?? null)
            <img src="{{ asset($setting->primary_logo_url) }}" alt="{{ $companyName }}"
                 style="max-height:30px;width:auto;max-width:130px;object-fit:contain;"/>
        @else
            <div class="nav-brand-icon"><i class="fas fa-chart-line"></i></div>
            <div class="nav-brand-name">Invest<span>Hub</span></div>
        @endif
    </a>

    <ul class="nav-links d-none d-lg-flex">
        @foreach($navLinks as $link)
            @php
                $exists   = Route::has($link['route']);
                $isActive = $exists && request()->routeIs($link['match']);
                $href     = $exists ? route($link['route']) : '#';
            @endphp
            <li>
                <a href="{{ $href }}"
                   class="{{ $isActive ? 'active' : '' }}">
                    @if(isset($link['icon'])) <i class="{{ $link['icon'] }} me-1 opacity-75"></i> @endif
                    {{ $link['label'] }}
                </a>
            </li>
        @endforeach
    </ul>

    <div class="nav-right">
        <a href="{{ route('home') }}" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1 text-decoration-none fw-semibold d-none d-sm-inline-flex align-items-center me-1" title="View Frontend Site">
            <i class="fas fa-globe me-1"></i> Website
        </a>

        @if($isAdmin && Route::has('settings.index'))
        <a href="{{ route('settings.index') }}" class="nav-icon-btn" title="Settings">
            <i class="fas fa-gear"></i>
        </a>
        @endif

        <div style="position:relative; margin-left:6px;">
            <button class="nav-user-btn" id="nav-user-btn" onclick="toggleNavDropdown()">
                <div class="nav-user-avatar">{{ strtoupper(substr($user->name ?? 'User', 0, 2)) }}</div>
                <div class="d-none d-md-flex flex-column text-start ms-2 me-1">
                    <span class="fw-bold text-dark lh-1 extra-small">{{ Str::limit($user->name ?? 'User', 14) }}</span>
                    <span class="text-uppercase extra-small font-monospace text-primary fw-semibold" style="font-size: 0.65rem;">{{ $roleName }}</span>
                </div>
                <i class="fas fa-chevron-down" style="font-size:0.6rem;color:#9ca3af;margin-left:4px;"></i>
            </button>

            <div class="nav-dropdown" id="nav-dropdown">
                <div class="dd-head">
                    <div class="dd-head-name">{{ $user->name ?? 'User' }}</div>
                    <div class="dd-head-email">{{ $user->email ?? '' }}</div>
                    <span class="badge bg-primary-subtle text-primary border border-primary border-opacity-25 rounded-pill px-2.5 py-0.5 extra-small fw-semibold mt-1 text-uppercase">
                        {{ $roleName }}
                    </span>
                </div>
                <a href="{{ route('profile.edit') }}" class="dd-link">
                    <i class="fas fa-user"></i> My Profile
                </a>
                <a href="{{ route('dashboard') }}" class="dd-link">
                    <i class="fas fa-gauge-high"></i> Dashboard
                </a>
                <a href="{{ route('withdrawals.index') }}" class="dd-link">
                    <i class="fas fa-money-bill-transfer"></i> Withdrawals
                </a>
                @if($isAdmin && Route::has('settings.index'))
                <a href="{{ route('settings.index') }}" class="dd-link">
                    <i class="fas fa-gear"></i> System Settings
                </a>
                @endif
                <div class="dd-sep"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dd-link danger">
                        <i class="fas fa-right-from-bracket"></i> Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

<!-- MOBILE OFFCANVAS SIDECANVAS MENU -->
<div class="offcanvas offcanvas-start border-0 shadow-lg" tabindex="-1" id="backendMobileOffcanvas" aria-labelledby="backendMobileOffcanvasLabel" style="width: 280px;">
    <div class="offcanvas-header bg-dark text-white py-3">
        <div class="d-flex align-items-center gap-2">
            <div class="nav-brand-icon"><i class="fas fa-chart-line"></i></div>
            <h5 class="offcanvas-title fw-bold text-white mb-0 fs-6" id="backendMobileOffcanvasLabel">
                {{ $companyName ?? 'InvestHub' }} <span class="badge bg-success rounded-pill extra-small ms-1">{{ ucfirst($roleName) }}</span>
            </h5>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body p-0 d-flex flex-column justify-content-between">
        <div class="p-3">
            <!-- User Info Card -->
            <div class="p-3 bg-light rounded-3 mb-3 border d-flex align-items-center gap-3">
                <div class="nav-user-avatar rounded-circle bg-primary text-white fw-bold d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    {{ strtoupper(substr($user->name ?? 'User', 0, 2)) }}
                </div>
                <div class="overflow-hidden">
                    <strong class="d-block text-dark text-truncate small fw-bold">{{ $user->name ?? 'User' }}</strong>
                    <small class="text-muted text-truncate d-block extra-small">{{ $user->email ?? '' }}</small>
                </div>
            </div>

            <!-- Nav Links -->
            <label class="extra-small text-uppercase fw-bold text-muted px-2 mb-2 d-block">Navigation Menu</label>
            <div class="nav flex-column gap-1">
                @foreach($navLinks as $link)
                    @php
                        $exists   = Route::has($link['route']);
                        $isActive = $exists && request()->routeIs($link['match']);
                        $href     = $exists ? route($link['route']) : '#';
                        $iconClass = $link['icon'] ?? 'fas fa-circle-notch';
                    @endphp
                    <a href="{{ $href }}" class="nav-link py-2.5 px-3 rounded-3 d-flex align-items-center gap-1 {{ $isActive ? 'bg-primary text-white fw-bold shadow-sm' : 'text-dark fw-semibold hover-bg-light' }}">
                        <i class="{{ $iconClass }} width-20 text-center"></i>
                        <span>{{ $link['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Footer / Actions -->
        <div class="p-3 border-top bg-light">
            <a href="{{ route('home') }}" class="btn btn-outline-success w-100 btn-sm mb-2 rounded-3 text-start px-3">
                <i class="fas fa-globe me-2"></i> Frontend Website
            </a>
            <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary w-100 btn-sm mb-2 rounded-3 text-start px-3">
                <i class="fas fa-user-gear me-2"></i> Edit Profile
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-danger w-100 btn-sm rounded-3 text-start px-3">
                    <i class="fas fa-right-from-bracket me-2"></i> Log Out
                </button>
            </form>
        </div>
    </div>
</div>
