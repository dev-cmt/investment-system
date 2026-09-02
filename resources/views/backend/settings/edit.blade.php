<x-backend-layout>
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1"><i class="fas fa-gear text-primary me-2"></i>System Settings</h4>
                <p class="text-muted small mb-0">Configure company details, branding, logos, and contact information.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm" role="alert">
                <i class="fas fa-check-circle me-1.5"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-4">
                <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="fas fa-building text-primary me-1.5"></i> General Information</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Company Name <span class="text-danger">*</span></label>
                            <input type="text" name="company_name" class="form-control form-control-sm @error('company_name') is-invalid @enderror" value="{{ old('company_name', $setting->company_name) }}" required>
                            @error('company_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Primary Contact Email</label>
                            <input type="email" name="email" class="form-control form-control-sm @error('email') is-invalid @enderror" value="{{ old('email', $setting->email) }}">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Secondary Email</label>
                            <input type="email" name="email2" class="form-control form-control-sm @error('email2') is-invalid @enderror" value="{{ old('email2', $setting->email2) }}">
                            @error('email2') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Alert / Notification Email</label>
                            <input type="email" name="alert_email" class="form-control form-control-sm @error('alert_email') is-invalid @enderror" value="{{ old('alert_email', $setting->alert_email) }}">
                            @error('alert_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Phone Number</label>
                            <input type="text" name="phone" class="form-control form-control-sm @error('phone') is-invalid @enderror" value="{{ old('phone', $setting->phone) }}">
                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Secondary Phone</label>
                            <input type="text" name="phone2" class="form-control form-control-sm @error('phone2') is-invalid @enderror" value="{{ old('phone2', $setting->phone2) }}">
                            @error('phone2') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small">Office Address</label>
                            <input type="text" name="address" class="form-control form-control-sm @error('address') is-invalid @enderror" value="{{ old('address', $setting->address) }}">
                            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small">Short Site Description</label>
                            <textarea name="description" class="form-control form-control-sm @error('description') is-invalid @enderror" rows="2">{{ old('description', $setting->description) }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small">Copyright Text</label>
                            <input type="text" name="copyright_text" class="form-control form-control-sm @error('copyright_text') is-invalid @enderror" value="{{ old('copyright_text', $setting->copyright_text) }}">
                            @error('copyright_text') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="fas fa-palette text-primary me-1.5"></i> Branding &amp; Logos</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Primary Logo</label>
                            <input type="file" name="logo" class="form-control form-control-sm @error('logo') is-invalid @enderror" accept="image/*">
                            @if($setting->logo)
                                <div class="mt-2 p-2 bg-light border rounded-3 text-center d-inline-block">
                                    <img src="{{ $setting->primary_logo_url }}" alt="Logo" style="max-height: 48px;">
                                </div>
                            @endif
                            @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Favicon</label>
                            <input type="file" name="favicon" class="form-control form-control-sm @error('favicon') is-invalid @enderror" accept="image/*">
                            @if($setting->favicon)
                                <div class="mt-2 p-2 bg-light border rounded-3 text-center d-inline-block">
                                    <img src="{{ $setting->favicon_url }}" alt="Favicon" style="max-height: 32px;">
                                </div>
                            @endif
                            @error('favicon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="fas fa-share-nodes text-primary me-1.5"></i> Social Media Links</h6>
                    <div class="row g-3 mb-4">
                        @php $socials = $setting->social_links ?? []; @endphp
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small"><i class="fab fa-facebook text-primary me-1"></i> Facebook URL</label>
                            <input type="url" name="social_facebook" class="form-control form-control-sm" value="{{ old('social_facebook', $socials['facebook'] ?? '') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small"><i class="fab fa-twitter text-info me-1"></i> Twitter / X URL</label>
                            <input type="url" name="social_twitter" class="form-control form-control-sm" value="{{ old('social_twitter', $socials['twitter'] ?? '') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small"><i class="fab fa-instagram text-danger me-1"></i> Instagram URL</label>
                            <input type="url" name="social_instagram" class="form-control form-control-sm" value="{{ old('social_instagram', $socials['instagram'] ?? '') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small"><i class="fab fa-youtube text-danger me-1"></i> YouTube URL</label>
                            <input type="url" name="social_youtube" class="form-control form-control-sm" value="{{ old('social_youtube', $socials['youtube'] ?? '') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small"><i class="fab fa-linkedin text-primary me-1"></i> LinkedIn URL</label>
                            <input type="url" name="social_linkedin" class="form-control form-control-sm" value="{{ old('social_linkedin', $socials['linkedin'] ?? '') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small"><i class="fab fa-whatsapp text-success me-1"></i> WhatsApp Number</label>
                            <input type="text" name="social_whatsapp" class="form-control form-control-sm" value="{{ old('social_whatsapp', $socials['whatsapp'] ?? '') }}">
                        </div>
                    </div>

                    <div class="col-12 pt-3 border-top">
                        <button type="submit" class="btn btn-primary btn-sm px-4 rounded-3 fw-semibold">
                            <i class="fas fa-save me-1.5"></i> Save Site Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-backend-layout>
