@php
  $companyName = $setting->company_name ?? config('app.name', 'InvestHub');
  $faviconUrl = $setting->favicon_url ?? null;
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @if($faviconUrl)
    <link rel="icon" type="image/x-icon" href="{{ $faviconUrl }}">
  @endif
  <title>{{ $companyName }} - Authentication</title>

  <!-- Bootstrap 5 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
  <!-- Font Awesome 6 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />

  <style>
    :root {
      --green-primary: #1a9e4f;
      --green-accent: #2ecc71;
      --green-dark: #157a3c;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    html, body {
      height: 100vh;
      width: 100vw;
      overflow: hidden !important;
    }

    body {
      font-family: 'Inter', sans-serif;
      background-color: #0b0f19;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      color: #f8fafc;
      padding: 8px 16px;
    }

    /* Ambient Background Glow Circles */
    .glow-circle {
      position: absolute;
      border-radius: 50%;
      filter: blur(90px);
      pointer-events: none;
      z-index: 0;
    }

    .glow-circle-1 {
      width: 380px;
      height: 380px;
      background: rgba(26, 158, 79, 0.2);
      top: -80px;
      right: -60px;
      animation: floatGlow 10s infinite alternate ease-in-out;
    }

    .glow-circle-2 {
      width: 340px;
      height: 340px;
      background: rgba(46, 204, 113, 0.14);
      bottom: -80px;
      left: -60px;
      animation: floatGlow 12s infinite alternate-reverse ease-in-out;
    }

    @keyframes floatGlow {
      0% { transform: translate(0, 0) scale(1); }
      50% { transform: translate(20px, 30px) scale(1.08); }
      100% { transform: translate(-15px, 15px) scale(0.95); }
    }

    /* Centered Auth Card Container */
    .auth-wrapper {
      position: relative;
      z-index: 10;
      width: 100%;
      max-width: 420px;
      max-height: 98vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      margin: auto;
      animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    @keyframes fadeInUp {
      0% {
        opacity: 0;
        transform: translateY(16px) scale(0.98);
      }
      100% {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }

    .auth-box {
      background: rgba(17, 24, 39, 0.88);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.08);
      border-radius: 20px;
      box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6), 0 0 25px rgba(26, 158, 79, 0.08);
      padding: 24px 26px 20px;
      position: relative;
      overflow-y: auto;
      max-height: 88vh;
      scrollbar-width: none;
      transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .auth-box::-webkit-scrollbar {
      display: none;
    }

    .auth-box:hover {
      border-color: rgba(46, 204, 113, 0.25);
      box-shadow: 0 25px 60px rgba(0, 0, 0, 0.7), 0 0 35px rgba(26, 158, 79, 0.15);
    }

    .auth-box::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 3px;
      background: linear-gradient(90deg, #1a9e4f, #2ecc71, #1a9e4f);
      background-size: 200% 100%;
      animation: gradientShift 4s linear infinite;
    }

    @keyframes gradientShift {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    /* Brand Logo */
    .brand-logo-wrap {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 14px;
      text-decoration: none;
      transition: transform 0.3s ease;
    }

    .brand-logo-wrap:hover {
      transform: translateY(-2px);
    }

    .brand-logo-icon {
      width: 36px;
      height: 36px;
      background: linear-gradient(135deg, #1a9e4f, #157a3c);
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #ffffff;
      font-size: 1.1rem;
      box-shadow: 0 4px 12px rgba(26, 158, 79, 0.4);
      animation: iconPulse 3s infinite alternate ease-in-out;
    }

    @keyframes iconPulse {
      0% { box-shadow: 0 4px 10px rgba(26, 158, 79, 0.3); }
      100% { box-shadow: 0 6px 18px rgba(46, 204, 113, 0.5); }
    }

    .brand-logo-text {
      font-size: 1.4rem;
      font-weight: 800;
      color: #ffffff;
      letter-spacing: -0.5px;
    }

    .brand-logo-text span {
      color: #2ecc71;
    }

    /* Input & Form Styling */
    .form-label-custom {
      color: #94a3b8;
      font-size: 0.78rem;
      font-weight: 600;
      margin-bottom: 3px;
    }

    .form-control-custom {
      background: #0f172a;
      border: 1px solid #1e293b;
      color: #ffffff;
      border-radius: 10px;
      padding: 8px 12px;
      font-size: 0.85rem;
      transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .form-control-custom::placeholder {
      color: #475569;
    }

    .form-control-custom:focus {
      background: #0f172a;
      border-color: #2ecc71;
      color: #ffffff;
      box-shadow: 0 0 0 3px rgba(46, 204, 113, 0.2);
    }

    .input-group-text-custom {
      background: #1e293b;
      border: 1px solid #1e293b;
      border-right: none;
      color: #64748b;
      border-top-left-radius: 10px;
      border-bottom-left-radius: 10px;
      font-size: 0.82rem;
      padding: 0 12px;
      transition: color 0.25s ease;
    }

    .input-group:focus-within .input-group-text-custom {
      color: #2ecc71;
      border-color: #2ecc71;
    }

    .input-group .form-control-custom {
      border-top-left-radius: 0;
      border-bottom-left-radius: 0;
    }

    /* Buttons & Links */
    .btn-auth-submit {
      background: linear-gradient(135deg, #1a9e4f, #157a3c);
      color: #ffffff;
      font-weight: 700;
      border-radius: 10px;
      padding: 10px;
      border: none;
      width: 100%;
      font-size: 0.88rem;
      letter-spacing: 0.3px;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      box-shadow: 0 4px 14px rgba(26, 158, 79, 0.35);
      cursor: pointer;
    }

    .btn-auth-submit:hover {
      background: linear-gradient(135deg, #2ecc71, #1a9e4f);
      color: #ffffff;
      transform: translateY(-1px);
      box-shadow: 0 7px 18px rgba(46, 204, 113, 0.45);
    }

    .btn-auth-submit:active {
      transform: translateY(0);
    }

    .auth-link-custom {
      color: #2ecc71;
      text-decoration: none;
      font-weight: 600;
      transition: all 0.2s ease;
    }

    .auth-link-custom:hover {
      color: #52e590;
      text-decoration: underline;
    }

    .back-home-link {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      color: #64748b;
      text-decoration: none;
      font-size: 0.78rem;
      font-weight: 500;
      transition: color 0.2s ease, transform 0.2s ease;
    }

    .back-home-link:hover {
      color: #94a3b8;
      transform: translateX(-2px);
    }
  </style>
</head>
<body>

  <!-- Ambient Glow Effects -->
  <div class="glow-circle glow-circle-1"></div>
  <div class="glow-circle glow-circle-2"></div>

  <!-- AUTH WRAPPER CARD (overflow: hidden on html/body prevents any scroll-y) -->
  <div class="auth-wrapper">
    <div class="auth-box">
      <!-- Brand Header -->
      <div class="text-center">
        <a href="{{ url('/') }}" class="brand-logo-wrap">
          @if($setting->primary_logo_url ?? null)
            <img src="{{ $setting->primary_logo_url }}" alt="{{ $companyName }}" style="max-height: 42px; width: auto; max-width: 160px; object-fit: contain; filter: drop-shadow(0 4px 12px rgba(0,0,0,0.15));" />
          @else
            <div class="brand-logo-icon">
              <i class="fas fa-chart-line"></i>
            </div>
            <span class="brand-logo-text">{{ $companyName }}</span>
          @endif
        </a>
      </div>

      <!-- Main Form Slot -->
      {{ $slot }}

    </div>

    <!-- Back to Home Link below card -->
    <div class="text-center mt-2">
      <a href="{{ url('/') }}" class="back-home-link">
        <i class="fas fa-arrow-left"></i> Return to {{ $companyName }} Home
      </a>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
