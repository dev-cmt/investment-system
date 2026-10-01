@php
    $setting = $setting ?? \App\Models\Setting::first();
    $companyName = $setting->company_name ?? 'InvestHub';
    $brandLogo = $setting->primary_logo_url ?? null;
    $companyEmail = $setting->email ?? 'support@investhub.com';
    $companyPhone = $setting->phone ?? '+880 1700-000000';
    $companyAddress = $setting->address ?? 'Dhaka, Bangladesh';
    $socialLinks = $setting->social_links ?? [];

    $facebookLink = $socialLinks['facebook'] ?? '';
    $twitterLink = $socialLinks['twitter'] ?? '';
    $instagramLink = $socialLinks['instagram'] ?? '';
    $youtubeLink = $socialLinks['youtube'] ?? '';
    $linkedinLink = $socialLinks['linkedin'] ?? '';
    $whatsappLink = $socialLinks['whatsapp'] ?? '';

    if (!empty($whatsappLink) && !preg_match('/^https?:\/\//i', $whatsappLink)) {
        $whatsappLink = 'https://wa.me/' . preg_replace('/\D+/', '', $whatsappLink);
    }
@endphp

<!-- ═══════════════════════════════════════════ FOOTER ═══════════════════════════════════════════ -->
<footer class="site-footer" id="footer-contact">
    <div class="container">
        <!-- ── Main Grid ── -->
        <div class="footer-grid">

            <!-- Col 1 – Brand & About -->
            <div class="footer-col footer-brand-col">
                <a href="{{ route('home') }}" class="footer-brand-link">
                    @if ($brandLogo)
                        <img src="{{ asset($brandLogo) }}" alt="{{ $companyName }}" class="footer-logo-img" />
                    @else
                        <div class="footer-logo-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <span class="footer-brand-name">{{ $companyName }}</span>
                    @endif
                </a>

                <!-- Social Links -->
                <div class="footer-socials">
                    @if(!empty($facebookLink))
                        <a href="{{ $facebookLink }}" class="footer-social-btn" aria-label="Facebook" title="Facebook" target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    @endif

                    @if(!empty($whatsappLink))
                        <a href="{{ $whatsappLink }}" class="footer-social-btn" aria-label="WhatsApp" title="WhatsApp" target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    @endif

                    @if(!empty($youtubeLink))
                        <a href="{{ $youtubeLink }}" class="footer-social-btn" aria-label="YouTube" title="YouTube" target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-youtube"></i>
                        </a>
                    @endif

                    @if(!empty($twitterLink))
                        <a href="{{ $twitterLink }}" class="footer-social-btn" aria-label="Twitter" title="Twitter" target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-x-twitter"></i>
                        </a>
                    @endif

                    @if(!empty($instagramLink))
                        <a href="{{ $instagramLink }}" class="footer-social-btn" aria-label="Instagram" title="Instagram" target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-instagram"></i>
                        </a>
                    @endif

                    @if(!empty($linkedinLink))
                        <a href="{{ $linkedinLink }}" class="footer-social-btn" aria-label="LinkedIn" title="LinkedIn" target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Col 2 – Quick Links -->
            <div class="footer-col">
                <h4 class="footer-col-heading">
                    <i class="fas fa-link me-2"></i>Quick Links
                </h4>
                <ul class="footer-nav-list">
                    <li><a href="{{ route('home') }}"><i class="fas fa-chevron-right"></i> Home</a></li>
                    <li><a href="{{ route('home') }}#howItWorks"><i class="fas fa-chevron-right"></i> How It Works</a>
                    </li>
                    <li><a href="{{ route('page.opportunities') }}"><i class="fas fa-chevron-right"></i> Active
                            Opportunities</a></li>
                    <li><a href="{{ route('page.about') }}"><i class="fas fa-chevron-right"></i> About Us</a></li>
                    <li><a href="{{ route('page.contact') }}"><i class="fas fa-chevron-right"></i> Contact Us</a></li>
                </ul>
            </div>

            <!-- Col 3 – Legal -->
            <div class="footer-col">
                <h4 class="footer-col-heading">
                    <i class="fas fa-scale-balanced me-2"></i>Legal
                </h4>
                <ul class="footer-nav-list">
                    <li><a href="{{ route('page.privacy') }}"><i class="fas fa-chevron-right"></i> Privacy Policy</a>
                    </li>
                    <li><a href="{{ route('page.terms') }}"><i class="fas fa-chevron-right"></i> Terms & Conditions</a>
                    </li>
                    @guest
                        <li><a href="{{ route('register') }}"><i class="fas fa-chevron-right"></i> Create Account</a></li>
                        <li><a href="{{ route('login') }}"><i class="fas fa-chevron-right"></i> Sign In</a></li>
                    @endguest
                    @auth
                        <li><a href="{{ route('dashboard') }}"><i class="fas fa-chevron-right"></i> Dashboard</a></li>
                    @endauth
                </ul>
            </div>

            <!-- Col 4 – Contact -->
            <div class="footer-col">
                <h4 class="footer-col-heading">
                    <i class="fas fa-headset me-2"></i>Contact Us
                </h4>
                <ul class="footer-contact-list">
                    <li>
                        <div class="footer-contact-icon"><i class="fas fa-envelope"></i></div>
                        <div>
                            <span class="footer-contact-label">Email</span>
                            <a href="mailto:{{ $companyEmail }}" class="footer-contact-value">{{ $companyEmail }}</a>
                        </div>
                    </li>
                    <li>
                        <div class="footer-contact-icon"><i class="fas fa-phone"></i></div>
                        <div>
                            <span class="footer-contact-label">Phone</span>
                            <a href="tel:{{ $companyPhone }}" class="footer-contact-value">{{ $companyPhone }}</a>
                        </div>
                    </li>
                    <li>
                        <div class="footer-contact-icon"><i class="fas fa-location-dot"></i></div>
                        <div>
                            <span class="footer-contact-label">Address</span>
                            <span class="footer-contact-value">{{ $companyAddress }}</span>
                        </div>
                    </li>
                </ul>
            </div>

        </div><!-- /footer-grid -->

        <!-- ── Trust Badges Bar ── -->
        <div class="footer-trust-bar">
            <div class="footer-trust-item">
                <i class="fas fa-shield-halved"></i>
                <span>Secure Investments</span>
            </div>
            <div class="footer-trust-sep"></div>
            <div class="footer-trust-item">
                <i class="fas fa-rotate"></i>
                <span>Weekly Payouts</span>
            </div>
            <div class="footer-trust-sep"></div>
            <div class="footer-trust-item">
                <i class="fas fa-users"></i>
                <span>Verified Investors</span>
            </div>
            <div class="footer-trust-sep"></div>
            <div class="footer-trust-item">
                <i class="fas fa-chart-line"></i>
                <span>Transparent Returns</span>
            </div>
        </div>

        <!-- ── Bottom Bar ── -->
        <div class="footer-bottom-bar">
            <p class="footer-copyright">
                &copy; {{ date('Y') }} <strong>{{ $companyName }}</strong>. All rights reserved.
                Built with <i class="fas fa-heart footer-heart"></i> for smart investors.
            </p>
            <div class="footer-bottom-links">
                <a href="{{ route('page.privacy') }}">Privacy Policy</a>
                <span class="footer-dot">·</span>
                <a href="{{ route('page.terms') }}">Terms & Conditions</a>
                <span class="footer-dot">·</span>
                <a href="{{ route('page.contact') }}">Contact</a>
            </div>
        </div>

    </div>
</footer>
<!-- Scroll-to-top button -->
<button id="scrollTopBtn" title="Back to top" aria-label="Scroll to top">
    <i class="fas fa-arrow-up"></i>
</button>
