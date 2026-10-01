{{-- ── CONTACT US EDITOR ── --}}
<div class="inv-table-card mb-0 p-0 overflow-hidden mb-4">
    <div class="db-panel-head bg-light border-bottom-0" style="border-radius: 14px 14px 0 0;">
        <ul class="nav tab-pill-nav gap-1 overflow-auto flex-nowrap pb-1 mb-0" id="contactEditorTabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#tab-contact-hero" type="button" role="tab">
                    <i class="fas fa-crown"></i> Hero Banner
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-contact-cards" type="button" role="tab">
                    <i class="fas fa-address-card"></i> Contact Cards
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-contact-form" type="button" role="tab">
                    <i class="fas fa-paper-plane"></i> Message Form
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-contact-sidebar" type="button" role="tab">
                    <i class="fas fa-clock"></i> Hours & WhatsApp
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-contact-faq" type="button" role="tab">
                    <i class="fas fa-circle-question"></i> FAQs
                </button>
            </li>
        </ul>
    </div>
</div>

<div class="tab-content" id="contactTabsContent">

    {{-- ── Tab: Hero ── --}}
    <div class="tab-pane fade show active" id="tab-contact-hero" role="tabpanel">
        <div class="inv-table-card p-4 mb-4">
            <h5 class="fw-bold text-dark mb-3"><i class="fas fa-crown text-warning me-2"></i> Page Hero Header</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="content[hero][show_badge]" id="contact_show_badge" value="1" {{ !empty($content['hero']['show_badge']) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold text-dark" for="contact_show_badge">Show Top Badge</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="fld-label">Badge Text</label>
                    <input type="text" name="content[hero][badge_text]" class="form-control" value="{{ $content['hero']['badge_text'] ?? 'Get In Touch' }}">
                </div>
                <div class="col-md-6">
                    <label class="fld-label">Page Title</label>
                    <input type="text" name="content[hero][title]" class="form-control" value="{{ $content['hero']['title'] ?? 'Contact Us' }}">
                </div>
                <div class="col-md-6">
                    <label class="fld-label">Badge Icon (FontAwesome)</label>
                    <input type="text" name="content[hero][badge_icon]" class="form-control" value="{{ $content['hero']['badge_icon'] ?? 'fas fa-headset' }}">
                </div>
            </div>
        </div>
    </div>

    {{-- ── Tab: Cards ── --}}
    <div class="tab-pane fade" id="tab-contact-cards" role="tabpanel">
        <div class="inv-table-card p-4 mb-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="fw-bold text-dark mb-0"><i class="fas fa-address-card text-success me-2"></i> Contact Info Cards</h5>
                <div class="form-check form-switch">
                    <input type="hidden" name="content[cards][show_section]" value="0">
                    <input class="form-check-input" type="checkbox" name="content[cards][show_section]" id="contact_show_cards" value="1" {{ !empty($content['cards']['show_section'] ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold text-dark" for="contact_show_cards">Show Cards Section</label>
                </div>
            </div>
            <div class="alert alert-info py-2 px-3 small rounded-3 mb-3">
                <i class="fas fa-info-circle me-1"></i> The actual email, phone, and address values are pulled dynamically from your <strong>General Settings</strong>.
            </div>
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="p-3 border rounded-3 bg-light">
                        <label class="fld-label">Email Card Label</label>
                        <input type="text" name="content[cards][email_label]" class="form-control form-control-sm mb-2" value="{{ $content['cards']['email_label'] ?? 'Email Us' }}">
                        <label class="fld-label">Email Subtitle</label>
                        <input type="text" name="content[cards][email_sub]" class="form-control form-control-sm" value="{{ $content['cards']['email_sub'] ?? 'We reply within 24 hours' }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 border rounded-3 bg-light">
                        <label class="fld-label">Phone Card Label</label>
                        <input type="text" name="content[cards][phone_label]" class="form-control form-control-sm mb-2" value="{{ $content['cards']['phone_label'] ?? 'Call Us' }}">
                        <label class="fld-label">Phone Subtitle</label>
                        <input type="text" name="content[cards][phone_sub]" class="form-control form-control-sm" value="{{ $content['cards']['phone_sub'] ?? 'Sat–Thu, 9:00 AM – 7:00 PM' }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 border rounded-3 bg-light">
                        <label class="fld-label">Address Card Label</label>
                        <input type="text" name="content[cards][address_label]" class="form-control form-control-sm mb-2" value="{{ $content['cards']['address_label'] ?? 'Visit Us' }}">
                        <label class="fld-label">Address Subtitle</label>
                        <input type="text" name="content[cards][address_sub]" class="form-control form-control-sm" value="{{ $content['cards']['address_sub'] ?? 'By appointment only' }}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Tab: Form ── --}}
    <div class="tab-pane fade" id="tab-contact-form" role="tabpanel">
        <div class="inv-table-card p-4 mb-4">
            <h5 class="fw-bold text-dark mb-3"><i class="fas fa-paper-plane text-primary me-2"></i> Contact Form Settings</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="fld-label">Form Card Title</label>
                    <input type="text" name="content[form][title]" class="form-control" value="{{ $content['form']['title'] ?? 'Send Us a Message' }}">
                </div>
                <div class="col-md-6">
                    <label class="fld-label">Form Subtitle</label>
                    <input type="text" name="content[form][subtitle]" class="form-control" value="{{ $content['form']['subtitle'] ?? '' }}">
                </div>
                <div class="col-12">
                    <label class="fld-label">Subject Options (One per line)</label>
                    @php
                        $subjectsText = isset($content['form']['subjects']) && is_array($content['form']['subjects'])
                            ? implode("\n", $content['form']['subjects'])
                            : "Investment Inquiry\nWithdrawal Help\nAccount Support\nPartnership\nOther";
                    @endphp
                    <textarea name="content[form][subjects_raw]" class="form-control" rows="5" placeholder="Investment Inquiry&#10;Withdrawal Help&#10;Account Support&#10;Other" oninput="this.nextElementSibling.value = JSON.stringify(this.value.split('\n').map(s=>s.trim()).filter(Boolean))">{{ $subjectsText }}</textarea>
                    <input type="hidden" name="content[form][subjects]" value="{{ json_encode($content['form']['subjects'] ?? ['Investment Inquiry', 'Withdrawal Help', 'Account Support', 'Partnership', 'Other']) }}">
                </div>
            </div>
        </div>
    </div>

    {{-- ── Tab: Hours & WhatsApp ── --}}
    <div class="tab-pane fade" id="tab-contact-sidebar" role="tabpanel">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="inv-table-card p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="fw-bold text-dark mb-0"><i class="fas fa-clock text-info me-2"></i> Business Hours</h5>
                        <div class="form-check form-switch">
                            <input type="hidden" name="content[hours][show_section]" value="0">
                            <input class="form-check-input" type="checkbox" name="content[hours][show_section]" id="contact_show_hours" value="1" {{ !empty($content['hours']['show_section'] ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold text-dark" for="contact_show_hours">Show</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="fld-label">Title</label>
                        <input type="text" name="content[hours][title]" class="form-control form-control-sm" value="{{ $content['hours']['title'] ?? 'Business Hours' }}">
                    </div>
                    @php $hoursItems = $content['hours']['items'] ?? []; @endphp
                    @for($hi = 0; $hi < 3; $hi++)
                        <div class="d-flex gap-2 mb-2">
                            <input type="text" name="content[hours][items][{{ $hi }}][day]" class="form-control form-control-sm" placeholder="Days (e.g. Sat - Thu)" value="{{ $hoursItems[$hi]['day'] ?? '' }}">
                            <input type="text" name="content[hours][items][{{ $hi }}][time]" class="form-control form-control-sm" placeholder="Time / Closed" value="{{ $hoursItems[$hi]['time'] ?? '' }}">
                        </div>
                    @endfor
                </div>
            </div>
            <div class="col-md-6">
                <div class="inv-table-card p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="fw-bold text-dark mb-0"><i class="fab fa-whatsapp text-success me-2"></i> WhatsApp Widget</h5>
                        <div class="form-check form-switch">
                            <input type="hidden" name="content[whatsapp][show_section]" value="0">
                            <input class="form-check-input" type="checkbox" name="content[whatsapp][show_section]" id="contact_show_wa" value="1" {{ !empty($content['whatsapp']['show_section'] ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold text-dark" for="contact_show_wa">Show</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="fld-label">Widget Title</label>
                        <input type="text" name="content[whatsapp][title]" class="form-control form-control-sm" value="{{ $content['whatsapp']['title'] ?? 'Chat on WhatsApp' }}">
                    </div>
                    <div class="mb-3">
                        <label class="fld-label">Description</label>
                        <textarea name="content[whatsapp][description]" class="form-control form-control-sm" rows="2">{{ $content['whatsapp']['description'] ?? '' }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="fld-label">Button Text</label>
                        <input type="text" name="content[whatsapp][btn_text]" class="form-control form-control-sm" value="{{ $content['whatsapp']['btn_text'] ?? 'Start Chat' }}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Tab: FAQs ── --}}
    <div class="tab-pane fade" id="tab-contact-faq" role="tabpanel">
        <div class="inv-table-card p-4 mb-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="fw-bold text-dark mb-0"><i class="fas fa-circle-question text-warning me-2"></i> Frequently Asked Questions</h5>
                <div class="form-check form-switch">
                    <input type="hidden" name="content[faq][show_section]" value="0">
                    <input class="form-check-input" type="checkbox" name="content[faq][show_section]" id="contact_show_faq" value="1" {{ !empty($content['faq']['show_section'] ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold text-dark" for="contact_show_faq">Show FAQ Section</label>
                </div>
            </div>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="fld-label">Section Label</label>
                    <input type="text" name="content[faq][label]" class="form-control" value="{{ $content['faq']['label'] ?? 'Common Questions' }}">
                </div>
                <div class="col-md-6">
                    <label class="fld-label">Section Title</label>
                    <input type="text" name="content[faq][title]" class="form-control" value="{{ $content['faq']['title'] ?? 'Frequently Asked Questions' }}">
                </div>
                <div class="col-12">
                    <label class="fld-label">Subtitle</label>
                    <input type="text" name="content[faq][subtitle]" class="form-control" value="{{ $content['faq']['subtitle'] ?? '' }}">
                </div>
            </div>

            <h6 class="fw-bold text-dark mb-2">FAQ Items</h6>
            <div class="row g-3">
                @php $faqItems = $content['faq']['items'] ?? []; @endphp
                @for($fi = 0; $fi < 5; $fi++)
                    <div class="col-12">
                        <div class="p-3 border rounded-3 bg-light">
                            <input type="text" name="content[faq][items][{{ $fi }}][q]" class="form-control form-control-sm fw-bold mb-2" placeholder="Question {{ $fi+1 }}" value="{{ $faqItems[$fi]['q'] ?? '' }}">
                            <textarea name="content[faq][items][{{ $fi }}][a]" class="form-control form-control-sm" rows="2" placeholder="Answer {{ $fi+1 }}">{{ $faqItems[$fi]['a'] ?? '' }}</textarea>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>

</div>
