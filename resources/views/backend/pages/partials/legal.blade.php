{{-- ── PRIVACY POLICY / TERMS & CONDITIONS EDITOR ── --}}
<div class="inv-table-card p-4 mb-4">
    <h5 class="fw-bold text-dark mb-3"><i class="fas fa-file-contract text-primary me-2"></i> Page Header & Intro</h5>
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" name="content[hero][show_badge]" id="legal_show_badge" value="1" {{ !empty($content['hero']['show_badge']) ? 'checked' : '' }}>
                <label class="form-check-label fw-semibold text-dark" for="legal_show_badge">Show Badge</label>
            </div>
        </div>
        <div class="col-md-6">
            <label class="fld-label">Badge Text</label>
            <input type="text" name="content[hero][badge_text]" class="form-control" value="{{ $content['hero']['badge_text'] ?? 'Legal Document' }}">
        </div>
        <div class="col-12">
            <label class="fld-label">Page Title</label>
            <input type="text" name="content[hero][title]" class="form-control" value="{{ $content['hero']['title'] ?? '' }}">
        </div>
        <div class="col-12">
            <label class="fld-label">Introduction Paragraph</label>
            <textarea name="content[intro]" class="form-control" rows="3">{{ $content['intro'] ?? '' }}</textarea>
            <small class="text-muted">Use <code>{company}</code>, <code>{email}</code>, <code>{phone}</code>, <code>{address}</code> for dynamic variables.</small>
        </div>
    </div>

    <div class="d-flex align-items-center justify-content-between mb-3 border-top pt-4">
        <h5 class="fw-bold text-dark mb-0"><i class="fas fa-list text-success me-2"></i> Document Policy Sections</h5>
        <button type="button" class="btn btn-sm btn-outline-success rounded-pill px-3" onclick="addLegalSection()">
            <i class="fas fa-plus me-1"></i> Add Section
        </button>
    </div>

    <div id="legalSectionsContainer">
        @php $sections = $content['sections'] ?? []; @endphp
        @forelse($sections as $si => $sec)
            <div class="p-3 border rounded-3 bg-light mb-3 legal-section-item position-relative" id="sec_row_{{ $si }}">
                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 rounded-circle" style="width:28px;height:28px;padding:0;display:flex;align-items:center;justify-content:center;" onclick="this.closest('.legal-section-item').remove()">
                    <i class="fas fa-times"></i>
                </button>
                <div class="row g-2 mb-2 pe-4">
                    <div class="col-md-2">
                        <label class="fld-label">Number</label>
                        <input type="text" name="content[sections][{{ $si }}][num]" class="form-control form-control-sm text-center fw-bold" value="{{ $sec['num'] ?? sprintf('%02d', $si+1) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="fld-label">Section ID (Anchor)</label>
                        <input type="text" name="content[sections][{{ $si }}][id]" class="form-control form-control-sm" value="{{ $sec['id'] ?? 'sec-' . ($si+1) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="fld-label">Section Title</label>
                        <input type="text" name="content[sections][{{ $si }}][title]" class="form-control form-control-sm fw-bold" value="{{ $sec['title'] ?? '' }}">
                    </div>
                </div>
                <div>
                    <label class="fld-label">Section Content</label>
                    <textarea name="content[sections][{{ $si }}][body]" class="form-control form-control-sm" rows="4">{{ $sec['body'] ?? '' }}</textarea>
                </div>
            </div>
        @empty
            <p class="text-muted small">No sections defined yet. Click "Add Section" above.</p>
        @endforelse
    </div>
</div>

<script>
    let sectionCounter = {{ count($sections) }};
    function addLegalSection() {
        const num = String(sectionCounter + 1).padStart(2, '0');
        const html = `
            <div class="p-3 border rounded-3 bg-light mb-3 legal-section-item position-relative" id="sec_row_${sectionCounter}">
                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 rounded-circle" style="width:28px;height:28px;padding:0;display:flex;align-items:center;justify-content:center;" onclick="this.closest('.legal-section-item').remove()">
                    <i class="fas fa-times"></i>
                </button>
                <div class="row g-2 mb-2 pe-4">
                    <div class="col-md-2">
                        <label class="fld-label">Number</label>
                        <input type="text" name="content[sections][${sectionCounter}][num]" class="form-control form-control-sm text-center fw-bold" value="${num}">
                    </div>
                    <div class="col-md-4">
                        <label class="fld-label">Section ID (Anchor)</label>
                        <input type="text" name="content[sections][${sectionCounter}][id]" class="form-control form-control-sm" value="sec-${sectionCounter + 1}">
                    </div>
                    <div class="col-md-6">
                        <label class="fld-label">Section Title</label>
                        <input type="text" name="content[sections][${sectionCounter}][title]" class="form-control form-control-sm fw-bold" placeholder="Section Title">
                    </div>
                </div>
                <div>
                    <label class="fld-label">Section Content</label>
                    <textarea name="content[sections][${sectionCounter}][body]" class="form-control form-control-sm" rows="4" placeholder="Write policy terms here..."></textarea>
                </div>
            </div>
        `;
        document.getElementById('legalSectionsContainer').insertAdjacentHTML('beforeend', html);
        sectionCounter++;
    }
</script>
