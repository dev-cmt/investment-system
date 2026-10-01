{{-- ── ABOUT US EDITOR ── --}}
<div class="inv-table-card mb-0 p-0 overflow-hidden mb-4">
    <div class="db-panel-head bg-light border-bottom-0" style="border-radius: 14px 14px 0 0;">
        <ul class="nav tab-pill-nav gap-1 overflow-auto flex-nowrap pb-1 mb-0" id="aboutEditorTabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#tab-about-hero" type="button" role="tab">
                    <i class="fas fa-crown"></i> Hero Banner
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-about-story" type="button" role="tab">
                    <i class="fas fa-book-open"></i> Our Story
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-about-values" type="button" role="tab">
                    <i class="fas fa-shield-halved"></i> Core Values
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-about-process" type="button" role="tab">
                    <i class="fas fa-list-ol"></i> Process Steps
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-about-cta" type="button" role="tab">
                    <i class="fas fa-bullhorn"></i> CTA Banner
                </button>
            </li>
        </ul>
    </div>
</div>

<div class="tab-content" id="aboutTabsContent">

    {{-- ── Tab: Hero ── --}}
    <div class="tab-pane fade show active" id="tab-about-hero" role="tabpanel">
        <div class="inv-table-card p-4 mb-4">
            <h5 class="fw-bold text-dark mb-3"><i class="fas fa-crown text-warning me-2"></i> Page Hero Header</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="content[hero][show_badge]" id="about_show_badge" value="1" {{ !empty($content['hero']['show_badge']) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold text-dark" for="about_show_badge">Show Top Badge</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="fld-label">Badge Text</label>
                    <input type="text" name="content[hero][badge_text]" class="form-control" value="{{ $content['hero']['badge_text'] ?? 'Our Company' }}">
                </div>
                <div class="col-md-6">
                    <label class="fld-label">Page Title</label>
                    <input type="text" name="content[hero][title]" class="form-control" value="{{ $content['hero']['title'] ?? 'About {company}' }}">
                    <small class="text-muted">Use <code>{company}</code> to automatically show site name.</small>
                </div>
                <div class="col-md-6">
                    <label class="fld-label">Badge Icon (FontAwesome)</label>
                    <input type="text" name="content[hero][badge_icon]" class="form-control" value="{{ $content['hero']['badge_icon'] ?? 'fas fa-building' }}">
                </div>
            </div>
        </div>
    </div>

    {{-- ── Tab: Story ── --}}
    <div class="tab-pane fade" id="tab-about-story" role="tabpanel">
        <div class="inv-table-card p-4 mb-4">
            <h5 class="fw-bold text-dark mb-3"><i class="fas fa-book-open text-primary me-2"></i> Company Story & Mission</h5>
            <div class="row g-3">
                <div class="col-12">
                    <div class="form-check form-switch mb-3">
                        <input type="hidden" name="content[story][show_section]" value="0">
                        <input class="form-check-input" type="checkbox" name="content[story][show_section]" id="about_show_story" value="1" {{ !empty($content['story']['show_section'] ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold text-dark" for="about_show_story">Show Story Section</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="fld-label">Section Tag Label</label>
                    <input type="text" name="content[story][label]" class="form-control" value="{{ $content['story']['label'] ?? 'Our Story' }}">
                </div>
                <div class="col-md-6">
                    <label class="fld-label">Section Title</label>
                    <input type="text" name="content[story][title]" class="form-control" value="{{ $content['story']['title'] ?? "Built on Trust,\nDriven by Results" }}">
                </div>
                <div class="col-md-6">
                    <label class="fld-label">Story Image Upload</label>
                    <input type="file" name="story_image" class="form-control">
                    @if(!empty($content['story']['image']))
                        <div class="mt-2"><small class="text-muted">Current: {{ $content['story']['image'] }}</small></div>
                    @endif
                </div>
                <div class="col-md-3">
                    <label class="fld-label">Badge Number</label>
                    <input type="text" name="content[story][badge_num]" class="form-control" value="{{ $content['story']['badge_num'] ?? '100%' }}">
                </div>
                <div class="col-md-3">
                    <label class="fld-label">Badge Subtitle</label>
                    <input type="text" name="content[story][badge_sub]" class="form-control" value="{{ $content['story']['badge_sub'] ?? 'Transparent Returns' }}">
                </div>

                <div class="col-12">
                    <label class="fld-label">Paragraph 1</label>
                    <textarea name="content[story][paragraphs][0]" class="form-control mb-2" rows="3">{{ $content['story']['paragraphs'][0] ?? '' }}</textarea>
                    <label class="fld-label">Paragraph 2</label>
                    <textarea name="content[story][paragraphs][1]" class="form-control" rows="3">{{ $content['story']['paragraphs'][1] ?? '' }}</textarea>
                </div>

                <div class="col-12">
                    <label class="fld-label">Key Highlights / Bullets</label>
                    <div class="row g-2">
                        @for($b = 0; $b < 4; $b++)
                            <div class="col-md-6">
                                <input type="text" name="content[story][bullets][{{ $b }}]" class="form-control form-control-sm" placeholder="Bullet {{ $b+1 }}" value="{{ $content['story']['bullets'][$b] ?? '' }}">
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Tab: Values ── --}}
    <div class="tab-pane fade" id="tab-about-values" role="tabpanel">
        <div class="inv-table-card p-4 mb-4">
            <h5 class="fw-bold text-dark mb-3"><i class="fas fa-shield-halved text-info me-2"></i> Core Values</h5>
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <div class="form-check form-switch mb-2">
                        <input type="hidden" name="content[values][show_section]" value="0">
                        <input class="form-check-input" type="checkbox" name="content[values][show_section]" id="about_show_values" value="1" {{ !empty($content['values']['show_section'] ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold text-dark" for="about_show_values">Show Values Section</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="fld-label">Section Label</label>
                    <input type="text" name="content[values][label]" class="form-control" value="{{ $content['values']['label'] ?? 'What We Stand For' }}">
                </div>
                <div class="col-md-6">
                    <label class="fld-label">Section Title</label>
                    <input type="text" name="content[values][title]" class="form-control" value="{{ $content['values']['title'] ?? 'Our Core Values' }}">
                </div>
                <div class="col-12">
                    <label class="fld-label">Subtitle</label>
                    <input type="text" name="content[values][subtitle]" class="form-control" value="{{ $content['values']['subtitle'] ?? '' }}">
                </div>
            </div>

            <h6 class="fw-bold text-dark mb-2">Value Cards</h6>
            <div class="row g-3">
                @php $valItems = $content['values']['items'] ?? []; @endphp
                @for($vi = 0; $vi < 4; $vi++)
                    <div class="col-md-6">
                        <div class="p-3 border rounded-3 bg-light">
                            <div class="d-flex gap-2 mb-2">
                                <input type="text" name="content[values][items][{{ $vi }}][icon]" class="form-control form-control-sm" placeholder="Icon (e.g. fas fa-lock)" value="{{ $valItems[$vi]['icon'] ?? 'fas fa-shield-halved' }}" style="max-width:180px;">
                                <input type="text" name="content[values][items][{{ $vi }}][title]" class="form-control form-control-sm fw-bold" placeholder="Card Title" value="{{ $valItems[$vi]['title'] ?? '' }}">
                            </div>
                            <textarea name="content[values][items][{{ $vi }}][text]" class="form-control form-control-sm" rows="2" placeholder="Description">{{ $valItems[$vi]['text'] ?? '' }}</textarea>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>

    {{-- ── Tab: Process ── --}}
    <div class="tab-pane fade" id="tab-about-process" role="tabpanel">
        <div class="inv-table-card p-4 mb-4">
            <h5 class="fw-bold text-dark mb-3"><i class="fas fa-list-ol text-success me-2"></i> How We Operate / Steps</h5>
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <div class="form-check form-switch mb-2">
                        <input type="hidden" name="content[process][show_section]" value="0">
                        <input class="form-check-input" type="checkbox" name="content[process][show_section]" id="about_show_process" value="1" {{ !empty($content['process']['show_section'] ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold text-dark" for="about_show_process">Show Process Section</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="fld-label">Section Label</label>
                    <input type="text" name="content[process][label]" class="form-control" value="{{ $content['process']['label'] ?? 'Our Process' }}">
                </div>
                <div class="col-md-6">
                    <label class="fld-label">Section Title</label>
                    <input type="text" name="content[process][title]" class="form-control" value="{{ $content['process']['title'] ?? 'How We Operate' }}">
                </div>
            </div>

            <h6 class="fw-bold text-dark mb-2">Process Steps (1 - 6)</h6>
            <div class="row g-3">
                @php $procSteps = $content['process']['steps'] ?? []; @endphp
                @for($pi = 0; $pi < 6; $pi++)
                    <div class="col-md-6">
                        <div class="p-3 border rounded-3 bg-light">
                            <div class="d-flex gap-2 mb-2">
                                <input type="text" name="content[process][steps][{{ $pi }}][num]" class="form-control form-control-sm text-center fw-bold" value="{{ $procSteps[$pi]['num'] ?? sprintf('%02d', $pi+1) }}" style="max-width:60px;">
                                <input type="text" name="content[process][steps][{{ $pi }}][title]" class="form-control form-control-sm fw-bold" placeholder="Step Title" value="{{ $procSteps[$pi]['title'] ?? '' }}">
                            </div>
                            <textarea name="content[process][steps][{{ $pi }}][text]" class="form-control form-control-sm" rows="2" placeholder="Step Details">{{ $procSteps[$pi]['text'] ?? '' }}</textarea>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>

    {{-- ── Tab: CTA ── --}}
    <div class="tab-pane fade" id="tab-about-cta" role="tabpanel">
        <div class="inv-table-card p-4 mb-4">
            <h5 class="fw-bold text-dark mb-3"><i class="fas fa-bullhorn text-warning me-2"></i> Call to Action Banner</h5>
            <div class="row g-3">
                <div class="col-12">
                    <div class="form-check form-switch mb-2">
                        <input type="hidden" name="content[cta][show_section]" value="0">
                        <input class="form-check-input" type="checkbox" name="content[cta][show_section]" id="about_show_cta" value="1" {{ !empty($content['cta']['show_section'] ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold text-dark" for="about_show_cta">Show CTA Banner</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="fld-label">CTA Title</label>
                    <input type="text" name="content[cta][title]" class="form-control" value="{{ $content['cta']['title'] ?? 'Ready to Invest With Us?' }}">
                </div>
                <div class="col-md-6">
                    <label class="fld-label">CTA Subtitle</label>
                    <input type="text" name="content[cta][subtitle]" class="form-control" value="{{ $content['cta']['subtitle'] ?? '' }}">
                </div>
                <div class="col-md-3">
                    <label class="fld-label">Button 1 Text</label>
                    <input type="text" name="content[cta][btn1_text]" class="form-control form-control-sm" value="{{ $content['cta']['btn1_text'] ?? 'Create Free Account' }}">
                </div>
                <div class="col-md-3">
                    <label class="fld-label">Button 1 Link</label>
                    <input type="text" name="content[cta][btn1_link]" class="form-control form-control-sm" value="{{ $content['cta']['btn1_link'] ?? '/register' }}">
                </div>
                <div class="col-md-3">
                    <label class="fld-label">Button 2 Text</label>
                    <input type="text" name="content[cta][btn2_text]" class="form-control form-control-sm" value="{{ $content['cta']['btn2_text'] ?? 'View Opportunities' }}">
                </div>
                <div class="col-md-3">
                    <label class="fld-label">Button 2 Link</label>
                    <input type="text" name="content[cta][btn2_link]" class="form-control form-control-sm" value="{{ $content['cta']['btn2_link'] ?? '/opportunities' }}">
                </div>
            </div>
        </div>
    </div>

</div>
