<x-backend-layout>
    <div class="inv-page-container">

        <!-- ── 1. Hero Header ───────────────────────────────────────────── -->
        <div class="inv-hero-header">
            <div class="d-flex align-items-center gap-3">
                <div class="inv-title-badge">
                    <i class="fas fa-gear"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-0 text-dark">System Configuration &amp; Settings</h4>
                    <p class="text-muted small mb-0 mt-0.5">Configure organization identity, official branding, contact points, and public social presence.</p>
                </div>
            </div>
        </div>

        <!-- ── Flash Notifications ──────────────────────────────────────── -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4 d-flex align-items-center gap-2" role="alert">
                <i class="fas fa-check-circle fs-5 text-success"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4 d-flex align-items-center gap-2" role="alert">
                <i class="fas fa-exclamation-triangle fs-5 text-danger"></i>
                <div>Please check the form for errors below.</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- ── 2. Settings Form Card ────────────────────────────────────── -->
        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-4">
                {{-- SECTION 1: General Company Information --}}
                <div class="col-12">
                    <div class="inv-table-card">
                        <div class="db-panel-head bg-light">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-building text-primary"></i>
                                <span class="db-panel-head-title">General Organization Profile</span>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="row g-3">
                                <div class="col-md-6 col-12">
                                    <label class="form-label fw-semibold small text-dark">Company Name <span class="text-danger">*</span></label>
                                    <input type="text" name="company_name" class="form-control @error('company_name') is-invalid @enderror" value="{{ old('company_name', $setting->company_name) }}" required>
                                    @error('company_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6 col-12">
                                    <label class="form-label fw-semibold small text-dark">Primary Contact Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="far fa-envelope"></i></span>
                                        <input type="email" name="email" class="form-control border-start-0 ps-1 @error('email') is-invalid @enderror" value="{{ old('email', $setting->email) }}">
                                    </div>
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6 col-12">
                                    <label class="form-label fw-semibold small text-dark">Secondary Support Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="far fa-envelope-open"></i></span>
                                        <input type="email" name="email2" class="form-control border-start-0 ps-1 @error('email2') is-invalid @enderror" value="{{ old('email2', $setting->email2) }}">
                                    </div>
                                    @error('email2') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6 col-12">
                                    <label class="form-label fw-semibold small text-dark">System Alerts &amp; Notification Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-bell"></i></span>
                                        <input type="email" name="alert_email" class="form-control border-start-0 ps-1 @error('alert_email') is-invalid @enderror" value="{{ old('alert_email', $setting->alert_email) }}">
                                    </div>
                                    @error('alert_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6 col-12">
                                    <label class="form-label fw-semibold small text-dark">Primary Hotline / Phone</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-phone"></i></span>
                                        <input type="text" name="phone" class="form-control border-start-0 ps-1 @error('phone') is-invalid @enderror" value="{{ old('phone', $setting->phone) }}">
                                    </div>
                                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6 col-12">
                                    <label class="form-label fw-semibold small text-dark">Secondary Phone Number</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-mobile-screen"></i></span>
                                        <input type="text" name="phone2" class="form-control border-start-0 ps-1 @error('phone2') is-invalid @enderror" value="{{ old('phone2', $setting->phone2) }}">
                                    </div>
                                    @error('phone2') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold small text-dark">Headquarters / Physical Office Address</label>
                                    <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address', $setting->address) }}" placeholder="e.g. Level 5, Tower A, Motijheel C/A, Dhaka">
                                    @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6 col-12">
                                    <label class="form-label fw-semibold small text-dark">Short Portal Description</label>
                                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3" placeholder="Brief summary of your investment platform...">{{ old('description', $setting->description) }}</textarea>
                                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6 col-12">
                                    <label class="form-label fw-semibold small text-dark">Copyright Footer Text</label>
                                    <textarea name="copyright_text" class="form-control @error('copyright_text') is-invalid @enderror" rows="3" placeholder="e.g. © 2026 InvestHub. All rights reserved.">{{ old('copyright_text', $setting->copyright_text) }}</textarea>
                                    @error('copyright_text') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECTION 2: Branding & Logos --}}
                <div class="col-12">
                    <div class="inv-table-card">
                        <div class="db-panel-head bg-light">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-palette text-primary"></i>
                                <span class="db-panel-head-title">Visual Branding &amp; Media Assets</span>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="row g-4">
                                <div class="col-md-6 col-12">
                                    <label class="form-label fw-semibold small text-dark">Primary Brand Logo</label>
                                    <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror" accept="image/*">
                                    <small class="text-muted extra-small">Recommended: PNG / SVG with transparent background.</small>
                                    @if($setting->logo)
                                        <div class="mt-3 p-3 bg-light border rounded-3 text-center d-flex align-items-center justify-content-center" style="min-height: 80px;">
                                            <img src="{{ $setting->primary_logo_url }}" alt="Primary Logo" style="max-height: 48px; width: auto; object-fit: contain;">
                                        </div>
                                    @endif
                                    @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6 col-12">
                                    <label class="form-label fw-semibold small text-dark">Browser Favicon</label>
                                    <input type="file" name="favicon" class="form-control @error('favicon') is-invalid @enderror" accept="image/*">
                                    <small class="text-muted extra-small">Recommended: Square 32x32 PNG or ICO format.</small>
                                    @if($setting->favicon)
                                        <div class="mt-3 p-3 bg-light border rounded-3 text-center d-flex align-items-center justify-content-center" style="min-height: 80px;">
                                            <img src="{{ $setting->favicon_url }}" alt="Favicon" style="max-height: 36px; width: auto; object-fit: contain;">
                                        </div>
                                    @endif
                                    @error('favicon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECTION 3: Social Media Connections --}}
                <div class="col-12">
                    <div class="inv-table-card">
                        <div class="db-panel-head bg-light">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-share-nodes text-primary"></i>
                                <span class="db-panel-head-title">Social Media Links &amp; Public Channels</span>
                            </div>
                        </div>
                        <div class="p-4">
                            @php $socials = $setting->social_links ?? []; @endphp
                            <div class="row g-3">
                                <div class="col-md-4 col-12">
                                    <label class="form-label fw-semibold small text-dark">Facebook Page URL</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-primary"><i class="fab fa-facebook-f"></i></span>
                                        <input type="url" name="social_facebook" class="form-control border-start-0 ps-1" value="{{ old('social_facebook', $socials['facebook'] ?? '') }}" placeholder="https://facebook.com/yourpage">
                                    </div>
                                </div>

                                <div class="col-md-4 col-12">
                                    <label class="form-label fw-semibold small text-dark">Twitter / X URL</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-dark"><i class="fab fa-x-twitter"></i></span>
                                        <input type="url" name="social_twitter" class="form-control border-start-0 ps-1" value="{{ old('social_twitter', $socials['twitter'] ?? '') }}" placeholder="https://x.com/yourhandle">
                                    </div>
                                </div>

                                <div class="col-md-4 col-12">
                                    <label class="form-label fw-semibold small text-dark">Instagram Profile URL</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-danger"><i class="fab fa-instagram"></i></span>
                                        <input type="url" name="social_instagram" class="form-control border-start-0 ps-1" value="{{ old('social_instagram', $socials['instagram'] ?? '') }}" placeholder="https://instagram.com/yourprofile">
                                    </div>
                                </div>

                                <div class="col-md-4 col-12">
                                    <label class="form-label fw-semibold small text-dark">YouTube Channel URL</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-danger"><i class="fab fa-youtube"></i></span>
                                        <input type="url" name="social_youtube" class="form-control border-start-0 ps-1" value="{{ old('social_youtube', $socials['youtube'] ?? '') }}" placeholder="https://youtube.com/@yourchannel">
                                    </div>
                                </div>

                                <div class="col-md-4 col-12">
                                    <label class="form-label fw-semibold small text-dark">LinkedIn Company URL</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-primary"><i class="fab fa-linkedin-in"></i></span>
                                        <input type="url" name="social_linkedin" class="form-control border-start-0 ps-1" value="{{ old('social_linkedin', $socials['linkedin'] ?? '') }}" placeholder="https://linkedin.com/company/yourcompany">
                                    </div>
                                </div>

                                <div class="col-md-4 col-12">
                                    <label class="form-label fw-semibold small text-dark">WhatsApp Contact Number</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-success"><i class="fab fa-whatsapp"></i></span>
                                        <input type="text" name="social_whatsapp" class="form-control border-start-0 ps-1" value="{{ old('social_whatsapp', $socials['whatsapp'] ?? '') }}" placeholder="+880 1712345678">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SUBMIT BUTTON --}}
                <div class="col-12">
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary btn-sm px-4 py-2.5 rounded-3 fw-semibold shadow-sm d-inline-flex align-items-center gap-2">
                            <i class="fas fa-save"></i>
                            <span>Save System Settings</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>

    </div>
</x-backend-layout>
