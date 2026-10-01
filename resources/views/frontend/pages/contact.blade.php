@extends('frontend.layouts.master')

@push('styles')
<style>
  /* ── Contact Page ── */
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
  .page-breadcrumb a { color: var(--green-primary); font-weight: 600; }
  .page-breadcrumb i { font-size: 0.65rem; }

  /* Contact Info Card */
  .contact-section { padding: 70px 0; }
  .contact-info-card {
    background: #fff; border: 1px solid var(--border-color); border-radius: 16px;
    padding: 28px; display: flex; align-items: flex-start; gap: 18px;
    transition: var(--transition); height: 100%;
  }
  .contact-info-card:hover { box-shadow: var(--card-shadow-h); transform: translateY(-3px); border-color: rgba(26,158,79,0.2); }
  .contact-info-icon {
    width: 54px; height: 54px; border-radius: 14px; flex-shrink: 0;
    background: var(--green-light); color: var(--green-primary);
    display: flex; align-items: center; justify-content: center; font-size: 1.3rem;
    transition: var(--transition);
  }
  .contact-info-card:hover .contact-info-icon { background: var(--green-primary); color: #fff; }
  .contact-info-label { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; color: var(--text-muted); margin-bottom: 6px; }
  .contact-info-value { font-size: 0.95rem; font-weight: 600; color: var(--text-dark); margin-bottom: 2px; }
  .contact-info-sub { font-size: 0.8rem; color: var(--text-muted); }

  /* Form */
  .contact-form-wrap {
    background: #fff; border: 1px solid var(--border-color); border-radius: 20px;
    padding: 40px; box-shadow: 0 4px 30px rgba(26,158,79,0.06);
  }
  .contact-form-title { font-size: 1.5rem; font-weight: 800; color: var(--text-dark); margin-bottom: 6px; }
  .contact-form-sub { font-size: 0.88rem; color: var(--text-muted); margin-bottom: 28px; }
  .form-group { margin-bottom: 20px; }
  .form-label-custom { font-size: 0.83rem; font-weight: 600; color: var(--text-dark); margin-bottom: 6px; display: block; }
  .form-control-custom {
    width: 100%; padding: 12px 16px; border: 2px solid var(--border-color);
    border-radius: 10px; font-size: 0.92rem; color: var(--text-dark);
    outline: none; transition: var(--transition); font-family: 'Inter', sans-serif;
    background: #fafafa;
  }
  .form-control-custom:focus { border-color: var(--green-primary); background: #fff; box-shadow: 0 0 0 4px rgba(26,158,79,0.1); }
  .form-control-custom::placeholder { color: #b0b7c3; }
  textarea.form-control-custom { resize: vertical; min-height: 140px; }
  select.form-control-custom { cursor: pointer; }
  .btn-contact-submit {
    background: linear-gradient(135deg, var(--green-primary), var(--green-dark));
    color: #fff; font-weight: 700; font-size: 0.95rem; padding: 14px 40px;
    border: none; border-radius: 10px; cursor: pointer; transition: var(--transition);
    box-shadow: 0 4px 16px rgba(26,158,79,0.3); width: 100%;
    position: relative; overflow: hidden;
  }
  .btn-contact-submit::before {
    content: ''; position: absolute; top: 0; left: -100%; width: 100%; height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
    transition: left 0.6s;
  }
  .btn-contact-submit:hover::before { left: 100%; }
  .btn-contact-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(26,158,79,0.45); }

  /* FAQ */
  .faq-section { padding: 70px 0; background: #f9fafb; }
  .faq-title { font-size: 2rem; font-weight: 800; color: var(--text-dark); letter-spacing: -0.5px; margin-bottom: 8px; }
  .faq-sub { font-size: 0.9rem; color: var(--text-muted); margin-bottom: 36px; }
  .faq-item { background: #fff; border: 1px solid var(--border-color); border-radius: 12px; margin-bottom: 12px; overflow: hidden; transition: var(--transition); }
  .faq-item:hover { border-color: rgba(26,158,79,0.2); }
  .faq-question {
    display: flex; align-items: center; justify-content: space-between; gap: 16px;
    padding: 18px 20px; cursor: pointer; font-size: 0.92rem; font-weight: 600;
    color: var(--text-dark); list-style: none;
  }
  .faq-question::-webkit-details-marker { display: none; }
  .faq-question .faq-icon { font-size: 0.8rem; color: var(--green-primary); transition: transform 0.3s; flex-shrink: 0; }
  details[open] .faq-question .faq-icon { transform: rotate(45deg); }
  .faq-answer { padding: 0 20px 18px; font-size: 0.87rem; color: var(--text-muted); line-height: 1.75; border-top: 1px solid var(--border-color); margin-top: 0; padding-top: 16px; }

  @media(max-width:768px) {
    .page-hero-title { font-size: 2rem; }
    .contact-form-wrap { padding: 24px; }
  }
</style>
@endpush

@section('content')

  @php
    $hero = $content['hero'] ?? [];
    $cards = $content['cards'] ?? [];
    $form = $content['form'] ?? [];
    $hours = $content['hours'] ?? [];
    $whatsapp = $content['whatsapp'] ?? [];
    $faq = $content['faq'] ?? [];

    $phoneClean = preg_replace('/[^0-9]/', '', $setting->phone ?? '8801700000000');
    $whatsappClean = !empty($setting->social_links['whatsapp']) ? preg_replace('/[^0-9]/', '', $setting->social_links['whatsapp']) : $phoneClean;
  @endphp

  {{-- ── Page Hero ── --}}
  <section class="page-hero">
    <div class="container">
      @if(!empty($hero['show_badge']))
        <div class="page-hero-badge">
          <i class="{{ $hero['badge_icon'] ?? 'fas fa-headset' }}"></i> {{ $hero['badge_text'] ?? 'Get In Touch' }}
        </div>
      @endif
      <h1 class="page-hero-title">Contact <span>Us</span></h1>
      <nav class="page-breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <i class="fas fa-chevron-right"></i>
        <span>Contact Us</span>
      </nav>
    </div>
  </section>

  {{-- ── Contact Cards ── --}}
  <section class="contact-section">
    <div class="container">

      <!-- Info Cards -->
      @if($cards['show_section'] ?? true)
        <div class="row g-4 mb-5">
          <div class="col-md-4" data-aos="fade-up" data-aos-delay="0">
            <div class="contact-info-card">
              <div class="contact-info-icon"><i class="fas fa-envelope"></i></div>
              <div>
                <div class="contact-info-label">{{ $cards['email_label'] ?? 'Email Us' }}</div>
                <div class="contact-info-value">
                  <a href="mailto:{{ $setting->email ?? 'support@investhub.com' }}" style="color:inherit;text-decoration:none;">
                    {{ $setting->email ?? 'support@investhub.com' }}
                  </a>
                </div>
                <div class="contact-info-sub">{{ $cards['email_sub'] ?? 'We reply within 24 hours' }}</div>
              </div>
            </div>
          </div>
          <div class="col-md-4" data-aos="fade-up" data-aos-delay="80">
            <div class="contact-info-card">
              <div class="contact-info-icon"><i class="fas fa-phone"></i></div>
              <div>
                <div class="contact-info-label">{{ $cards['phone_label'] ?? 'Call Us' }}</div>
                <div class="contact-info-value">
                  <a href="tel:{{ $setting->phone ?? '+8801700000000' }}" style="color:inherit;text-decoration:none;">
                    {{ $setting->phone ?? '+880 1700-000000' }}
                  </a>
                </div>
                <div class="contact-info-sub">{{ $cards['phone_sub'] ?? 'Sat–Thu, 9:00 AM – 7:00 PM' }}</div>
              </div>
            </div>
          </div>
          <div class="col-md-4" data-aos="fade-up" data-aos-delay="160">
            <div class="contact-info-card">
              <div class="contact-info-icon"><i class="fas fa-location-dot"></i></div>
              <div>
                <div class="contact-info-label">{{ $cards['address_label'] ?? 'Visit Us' }}</div>
                <div class="contact-info-value">{{ $setting->address ?? 'Dhaka, Bangladesh' }}</div>
                <div class="contact-info-sub">{{ $cards['address_sub'] ?? 'By appointment only' }}</div>
              </div>
            </div>
          </div>
        </div>
      @endif

      <!-- Form + Sidebar -->
      <div class="row g-5">

        <!-- Contact Form -->
        <div class="col-lg-7" data-aos="fade-right">
          <div class="contact-form-wrap">
            <div class="contact-form-title">{{ $form['title'] ?? 'Send Us a Message' }}</div>
            <div class="contact-form-sub">{{ $form['subtitle'] ?? 'Fill out the form and our team will get back to you within 24 hours.' }}</div>

            @if(session('contact_success'))
              <div class="alert alert-success rounded-3 mb-4" style="font-size:0.88rem;">
                <i class="fas fa-circle-check me-2"></i>{{ session('contact_success') }}
              </div>
            @endif

            <form action="{{ route('page.contact.submit') }}" method="POST" id="contactForm">
              @csrf
              <div class="row g-3">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="form-label-custom" for="contact_name">Full Name *</label>
                    <input type="text" id="contact_name" name="name" class="form-control-custom" placeholder="Your full name" required value="{{ old('name') }}">
                    @error('name')<div style="color:red;font-size:0.8rem;margin-top:4px;">{{ $message }}</div>@enderror
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="form-label-custom" for="contact_email">Email Address *</label>
                    <input type="email" id="contact_email" name="email" class="form-control-custom" placeholder="your@email.com" required value="{{ old('email') }}">
                    @error('email')<div style="color:red;font-size:0.8rem;margin-top:4px;">{{ $message }}</div>@enderror
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="form-label-custom" for="contact_phone">Phone Number</label>
                    <input type="tel" id="contact_phone" name="phone" class="form-control-custom" placeholder="+880 1XXXXXXXXX" value="{{ old('phone') }}">
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="form-label-custom" for="contact_subject">Subject *</label>
                    <select id="contact_subject" name="subject" class="form-control-custom" required>
                      <option value="">Select a subject…</option>
                      @php
                        $subjects = $form['subjects'] ?? ['Investment Inquiry', 'Withdrawal Help', 'Account Support', 'Partnership', 'Other'];
                      @endphp
                      @foreach($subjects as $subj)
                        <option value="{{ $subj }}" {{ old('subject') == $subj ? 'selected' : '' }}>{{ $subj }}</option>
                      @endforeach
                    </select>
                    @error('subject')<div style="color:red;font-size:0.8rem;margin-top:4px;">{{ $message }}</div>@enderror
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <label class="form-label-custom" for="contact_message">Message *</label>
                    <textarea id="contact_message" name="message" class="form-control-custom" placeholder="Write your message here…" required>{{ old('message') }}</textarea>
                    @error('message')<div style="color:red;font-size:0.8rem;margin-top:4px;">{{ $message }}</div>@enderror
                  </div>
                </div>
                <div class="col-12">
                  <button type="submit" class="btn-contact-submit" id="contactSubmitBtn">
                    <i class="fas fa-paper-plane me-2"></i>Send Message
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>

        <!-- Sidebar Info -->
        <div class="col-lg-5" data-aos="fade-left">
          <!-- Business Hours -->
          @if(($hours['show_section'] ?? true) && !empty($hours['items']))
            <div style="background:#f9fafb;border:1px solid var(--border-color);border-radius:16px;padding:28px;margin-bottom:24px;">
              <h5 style="font-size:1rem;font-weight:800;color:var(--text-dark);margin-bottom:18px;">
                <i class="fas fa-clock text-success me-2"></i>{{ $hours['title'] ?? 'Business Hours' }}
              </h5>
              @foreach($hours['items'] as $h)
                <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid var(--border-color);font-size:0.87rem;">
                  <span style="color:var(--text-muted);">{{ $h['day'] ?? '' }}</span>
                  <span style="font-weight:600;color:{{ ($h['time'] ?? '') === 'Closed' ? '#ef4444' : 'var(--green-primary)' }};">{{ $h['time'] ?? '' }}</span>
                </div>
              @endforeach
            </div>
          @endif

          <!-- Connect via WhatsApp -->
          @if($whatsapp['show_section'] ?? true)
            <div style="background:linear-gradient(135deg,#25D366,#128C7E);border-radius:16px;padding:28px;color:#fff;text-align:center;">
              <i class="fab fa-whatsapp" style="font-size:2.5rem;margin-bottom:12px;display:block;"></i>
              <h5 style="font-size:1rem;font-weight:800;margin-bottom:8px;">{{ $whatsapp['title'] ?? 'Chat on WhatsApp' }}</h5>
              <p style="font-size:0.83rem;opacity:0.85;margin-bottom:18px;">{{ $whatsapp['description'] ?? 'For quick answers, reach us directly on WhatsApp.' }}</p>
              <a href="https://wa.me/{{ $whatsappClean }}" target="_blank" rel="noopener"
                 style="display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,0.2);backdrop-filter:blur(4px);color:#fff;font-weight:700;padding:12px 28px;border-radius:10px;text-decoration:none;font-size:0.9rem;border:1px solid rgba(255,255,255,0.3);transition:all 0.3s;"
                 onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                <i class="fab fa-whatsapp"></i> {{ $whatsapp['btn_text'] ?? 'Start Chat' }}
              </a>
            </div>
          @endif
        </div>

      </div>
    </div>
  </section>

  {{-- ── FAQ ── --}}
  @if(($faq['show_section'] ?? true) && !empty($faq['items']))
    <section class="faq-section">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-8 text-center mb-5" data-aos="fade-up">
            <div style="font-size:0.78rem;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:var(--green-primary);margin-bottom:8px;">{{ $faq['label'] ?? 'Common Questions' }}</div>
            <h2 class="faq-title">{{ $faq['title'] ?? 'Frequently Asked Questions' }}</h2>
            @if(!empty($faq['subtitle']))
              <p class="faq-sub">{{ $faq['subtitle'] }}</p>
            @endif
          </div>
        </div>
        <div class="row justify-content-center">
          <div class="col-lg-8">
            @foreach($faq['items'] as $i => $item)
              <details class="faq-item" data-aos="fade-up" data-aos-delay="{{ $i * 60 }}" {{ $i === 0 ? 'open' : '' }}>
                <summary class="faq-question">
                  {{ $item['q'] ?? '' }}
                  <span class="faq-icon"><i class="fas fa-plus"></i></span>
                </summary>
                <div class="faq-answer">{{ $item['a'] ?? '' }}</div>
              </details>
            @endforeach
          </div>
        </div>
      </div>
    </section>
  @endif

@endsection
