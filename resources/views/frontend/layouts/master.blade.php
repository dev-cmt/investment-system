@php
  $setting = $setting ?? \App\Models\Setting::first();
  $companyName = $setting->company_name ?? 'InvestHub';
  $companyDescription = $setting->description ?? 'Invest in real import products and earn weekly profit.';
  $faviconUrl = $setting->favicon_url ?? null;
  $brandLogo = $setting->primary_logo_url ?? null;

  $seoMetaTitle = !empty($homeContent['seo']['meta_title']) ? $homeContent['seo']['meta_title'] : ($title ?? ($companyName . ' - Invest in Real Products, Earn Weekly Profit'));
  $seoMetaDesc = !empty($homeContent['seo']['meta_description']) ? $homeContent['seo']['meta_description'] : ($companyName . ' - ' . $companyDescription);
  $seoMetaKeywords = !empty($homeContent['seo']['meta_keywords']) ? $homeContent['seo']['meta_keywords'] : null;
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  @if($faviconUrl)
    <link rel="icon" type="image/x-icon" href="{{ $faviconUrl }}">
  @endif
  <meta name="description" content="{{ $seoMetaDesc }}" />
  @if($seoMetaKeywords)
    <meta name="keywords" content="{{ $seoMetaKeywords }}" />
  @endif
  <title>{{ $seoMetaTitle }}</title>
  <!-- Fonts -->
  <link rel="stylesheet" href="{{ asset('css/fonts.css') }}" />
  <!-- Bootstrap 5 -->
  <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />
  <!-- Font Awesome 6 -->
  <link rel="stylesheet" href="{{ asset('css/fontawesome.min.css') }}" />
  <!-- AOS -->
  <link rel="stylesheet" href="{{ asset('css/aos.css') }}" />
  <!-- GLightbox -->
  <link rel="stylesheet" href="{{ asset('css/glightbox.min.css') }}" />
  <!-- Swiper -->
  <link rel="stylesheet" href="{{ asset('css/swiper-bundle.min.css') }}" />
  <!-- Toastr -->
  <link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}" />
  <!-- Custom -->
  <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
  @stack('styles')
</head>

<body>
  <!-- PAGE LOADER -->
  <div id="pageLoader">
    <div class="loader-inner">
      <div class="loader-ring"></div>
      <div class="loader-text">{{ $companyName }}</div>
    </div>
  </div>

  <!-- HEADER / NAVBAR -->
  @include('frontend.partials.header')

  <!-- MAIN CONTENT -->
  <main>
    {{ $slot ?? '' }}
    @yield('content')
  </main>

  <!-- FOOTER -->
  @include('frontend.partials.footer')

  <!-- SCRIPTS -->
  <script src="{{ asset('js/jquery.min.js') }}"></script>
  <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('js/aos.js') }}"></script>
  <script src="{{ asset('js/glightbox.min.js') }}"></script>
  <script src="{{ asset('js/swiper-bundle.min.js') }}"></script>
  <script src="{{ asset('js/toastr.min.js') }}"></script>
  <script src="{{ asset('js/main.js') }}"></script>
  @stack('scripts')
</body>
</html>
