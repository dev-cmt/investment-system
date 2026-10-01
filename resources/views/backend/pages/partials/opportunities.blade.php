{{-- ── ACTIVE OPPORTUNITIES EDITOR ── --}}
<div class="inv-table-card p-4 mb-4">
    <h5 class="fw-bold text-dark mb-3"><i class="fas fa-fire text-danger me-2"></i> Page Hero Header</h5>
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" name="content[hero][show_badge]" id="opps_show_badge" value="1" {{ !empty($content['hero']['show_badge'] ?? true) ? 'checked' : '' }}>
                <label class="form-check-label fw-semibold text-dark" for="opps_show_badge">Show Live Badge</label>
            </div>
        </div>
        <div class="col-md-6">
            <label class="fld-label">Badge Text</label>
            <input type="text" name="content[hero][badge_text]" class="form-control" value="{{ $content['hero']['badge_text'] ?? 'Live Opportunities' }}">
        </div>
        <div class="col-12">
            <label class="fld-label">Page Title</label>
            <input type="text" name="content[hero][title]" class="form-control" value="{{ $content['hero']['title'] ?? 'Active Opportunities' }}">
        </div>
    </div>

    <h5 class="fw-bold text-dark mb-3 border-top pt-4"><i class="fas fa-lock text-warning me-2"></i> Guest Sign-in Prompt Banner</h5>
    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" name="content[guest_banner][show]" id="opps_show_guest" value="1" {{ !empty($content['guest_banner']['show'] ?? true) ? 'checked' : '' }}>
                <label class="form-check-label fw-semibold text-dark" for="opps_show_guest">Show Guest Banner for non-logged-in visitors</label>
            </div>
        </div>
        <div class="col-md-6">
            <label class="fld-label">Banner Title</label>
            <input type="text" name="content[guest_banner][title]" class="form-control" value="{{ $content['guest_banner']['title'] ?? 'Sign In to View Full Details & Invest' }}">
        </div>
        <div class="col-md-6">
            <label class="fld-label">Banner Text</label>
            <textarea name="content[guest_banner][text]" class="form-control" rows="2">{{ $content['guest_banner']['text'] ?? '' }}</textarea>
        </div>
        <div class="col-md-3">
            <label class="fld-label">Button 1 Text</label>
            <input type="text" name="content[guest_banner][btn1_text]" class="form-control form-control-sm" value="{{ $content['guest_banner']['btn1_text'] ?? 'Create Free Account' }}">
        </div>
        <div class="col-md-3">
            <label class="fld-label">Button 2 Text</label>
            <input type="text" name="content[guest_banner][btn2_text]" class="form-control form-control-sm" value="{{ $content['guest_banner']['btn2_text'] ?? 'Sign In' }}">
        </div>
    </div>

    <h5 class="fw-bold text-dark mb-3 border-top pt-4"><i class="fas fa-box-open text-muted me-2"></i> Empty State Message</h5>
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <label class="fld-label">Empty State Title</label>
            <input type="text" name="content[empty_state][title]" class="form-control" value="{{ $content['empty_state']['title'] ?? 'No Active Opportunities Right Now' }}">
        </div>
        <div class="col-md-6">
            <label class="fld-label">Button Text</label>
            <input type="text" name="content[empty_state][btn_text]" class="form-control" value="{{ $content['empty_state']['btn_text'] ?? 'Get Notified' }}">
        </div>
        <div class="col-12">
            <label class="fld-label">Empty State Description</label>
            <textarea name="content[empty_state][text]" class="form-control" rows="2">{{ $content['empty_state']['text'] ?? '' }}</textarea>
        </div>
    </div>

    <h5 class="fw-bold text-dark mb-3 border-top pt-4"><i class="fas fa-cubes text-info me-2"></i> Opportunity Details: Bottom Features Strip</h5>
    <div class="row g-3">
        <div class="col-12">
            <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" name="content[bottom_features][show_section]" id="opps_show_bottom_features" value="1" {{ !empty($content['bottom_features']['show_section'] ?? true) ? 'checked' : '' }}>
                <label class="form-check-label fw-semibold text-dark" for="opps_show_bottom_features">Show Bottom Features Strip on Details Page</label>
            </div>
            <p class="text-muted small mb-0">
                Dynamic placeholders available in Labels and Values:
                <code>{time_label}</code>, <code>{expected_import_days}</code>, <code>{msg_profit_payment}</code>, <code>{return_type}</code>, <code>{type}</code>, <code>{profit_percentage}</code>, <code>{target_amount}</code>, <code>{unit_cost}</code>, <code>{per_piece_profit}</code>
            </p>
        </div>
        @php
            $bfItems = $content['bottom_features']['items'] ?? \App\Models\Page::defaultOpportunitiesContent()['bottom_features']['items'];
        @endphp
        @for($bi = 0; $bi < 4; $bi++)
            <div class="col-md-6">
                <div class="p-3 border rounded-3 bg-light">
                    <div class="fw-bold text-dark small mb-2"><i class="fas fa-tag text-primary me-1"></i> Feature Card {{ $bi + 1 }}</div>
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="fld-label">Icon (FontAwesome)</label>
                            <input type="text" name="content[bottom_features][items][{{ $bi }}][icon]" class="form-control form-control-sm" placeholder="fas fa-calendar-alt" value="{{ $bfItems[$bi]['icon'] ?? '' }}">
                        </div>
                        <div class="col-6">
                            <label class="fld-label">Color Style</label>
                            <select name="content[bottom_features][items][{{ $bi }}][class]" class="form-select form-select-sm">
                                @php $curClass = $bfItems[$bi]['class'] ?? 'bg-primary-subtle text-primary'; @endphp
                                <option value="bg-primary-subtle text-primary" {{ $curClass == 'bg-primary-subtle text-primary' ? 'selected' : '' }}>Blue (Primary)</option>
                                <option value="bg-success-subtle text-success" {{ $curClass == 'bg-success-subtle text-success' ? 'selected' : '' }}>Green (Success)</option>
                                <option value="bg-info-subtle text-info" {{ $curClass == 'bg-info-subtle text-info' ? 'selected' : '' }}>Cyan (Info)</option>
                                <option value="bg-warning-subtle text-warning" {{ $curClass == 'bg-warning-subtle text-warning' ? 'selected' : '' }}>Yellow (Warning)</option>
                                <option value="bg-danger-subtle text-danger" {{ $curClass == 'bg-danger-subtle text-danger' ? 'selected' : '' }}>Red (Danger)</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="fld-label">Label</label>
                            <input type="text" name="content[bottom_features][items][{{ $bi }}][label]" class="form-control form-control-sm" placeholder="Label / {time_label}" value="{{ $bfItems[$bi]['label'] ?? '' }}">
                        </div>
                        <div class="col-6">
                            <label class="fld-label">Value</label>
                            <input type="text" name="content[bottom_features][items][{{ $bi }}][value]" class="form-control form-control-sm" placeholder="Value / {expected_import_days} Days" value="{{ $bfItems[$bi]['value'] ?? '' }}">
                        </div>
                    </div>
                </div>
            </div>
        @endfor
    </div>
</div>
