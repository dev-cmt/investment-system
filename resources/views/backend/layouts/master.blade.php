@php
    $companyName = $setting->company_name ?? config('app.name', 'InvestHub');
    $faviconUrl  = $setting->favicon_url ?? null;
    $user        = Auth::user();
    $isAdmin     = $user && ($user->hasRole(['admin', 'superadmin']) || $user->can('view posts'));

    if ($isAdmin) {
        $navLinks = [
            ['label' => 'Dashboard',   'route' => 'dashboard',         'match' => 'dashboard',         'icon' => 'fas fa-gauge-high'],
            ['label' => 'Posts',       'route' => 'posts.index',       'match' => 'posts.*',           'icon' => 'fas fa-boxes-stacked'],
            ['label' => 'Investments', 'route' => 'investments.index', 'match' => 'investments.*',     'icon' => 'fas fa-hand-holding-dollar'],
            ['label' => 'Payments',    'route' => 'payments.index',    'match' => 'payments.*',        'icon' => 'fas fa-receipt'],
            ['label' => 'Withdrawals', 'route' => 'withdrawals.index', 'match' => 'withdrawals.*',     'icon' => 'fas fa-money-bill-transfer'],
            ['label' => 'Clients',     'route' => 'users.index',       'match' => 'users.*',           'icon' => 'fas fa-users'],
            ['label' => 'Roles',       'route' => 'roles.index',       'match' => 'roles.*',           'icon' => 'fas fa-user-shield'],
            ['label' => 'Settings',    'route' => 'settings.index',    'match' => 'settings.*',        'icon' => 'fas fa-gear'],
        ];
    } else {
        $navLinks = [
            ['label' => 'Dashboard',      'route' => 'dashboard',         'match' => 'dashboard',       'icon' => 'fas fa-gauge-high'],
            ['label' => 'My Investments', 'route' => 'investments.index', 'match' => 'investments.*',   'icon' => 'fas fa-hand-holding-dollar'],
            ['label' => 'My Payments',    'route' => 'payments.index',    'match' => 'payments.*',      'icon' => 'fas fa-receipt'],
            ['label' => 'Withdraw Funds', 'route' => 'withdrawals.index', 'match' => 'withdrawals.*',   'icon' => 'fas fa-money-bill-transfer'],
        ];
    }
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if($faviconUrl)
        <link rel="icon" type="image/x-icon" href="{{ $faviconUrl }}">
    @endif
    <title>{{ $companyName }} - Admin Dashboard</title>
    <!-- Fonts & Font Awesome 6 -->
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/fontawesome.min.css') }}"/>
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}"/>
    <!-- Custom Backend Stylesheet -->
    <link rel="stylesheet" href="{{ asset('css/backend.css') }}"/>
    @stack('styles')
</head>
<body>

@include('backend.partials.header')

<!-- ── PAGE CONTENT ────────────────────────────────────── -->
<div id="page-content">
    <main>{{ $slot ?? '' }} @yield('content')</main>
</div>

<!-- SCRIPTS -->
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

<script>
    function toggleMobileNav() {
        document.getElementById('mobile-nav').classList.toggle('open');
    }
    function toggleNavDropdown() {
        document.getElementById('nav-dropdown').classList.toggle('open');
    }
    document.addEventListener('click', function(e) {
        var btn = document.getElementById('nav-user-btn');
        var dd  = document.getElementById('nav-dropdown');
        if (btn && dd && !btn.contains(e.target) && !dd.contains(e.target)) {
            dd.classList.remove('open');
        }
    });

    // Clean up residual modal backdrops on hide
    document.addEventListener('hidden.bs.modal', function () {
        document.querySelectorAll('.modal-backdrop').forEach(function(b) { b.remove(); });
        document.body.classList.remove('modal-open');
        document.body.style.removeProperty('padding-right');
        document.body.style.removeProperty('overflow');
    });
</script>

@stack('scripts')
</body>
</html>
