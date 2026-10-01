<x-backend-layout>

<div class="inv-page-container">

    {{-- ── Header ── --}}
    <div class="inv-hero-header mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="inv-title-badge">
                <i class="fas fa-envelope-open"></i>
            </div>
            <div>
                <div class="d-flex align-items-center gap-2">
                    <h4 class="fw-bold mb-0 text-dark">Message from {{ $contact->name }}</h4>
                    @if($contact->status === 'unread')
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2.5 py-1 extra-small">Unread</span>
                    @elseif($contact->status === 'read')
                        <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-2.5 py-1 extra-small">Read</span>
                    @else
                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 extra-small">Replied</span>
                    @endif
                </div>
                <p class="text-muted small mb-0 mt-0.5">Received on {{ $contact->created_at->format('l, d F Y - h:i A') }}</p>
            </div>
        </div>
        <div>
            <a href="{{ route('contacts.index') }}" class="btn btn-outline-secondary btn-sm rounded-3 px-3">
                <i class="fas fa-arrow-left me-1"></i> Back to Messages
            </a>
        </div>
    </div>

    {{-- ── Flash Notifications ── --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4 d-flex align-items-center gap-2" role="alert">
            <i class="fas fa-check-circle fs-5 text-success"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        {{-- Message Details --}}
        <div class="col-lg-8">
            <div class="inv-table-card p-4 mb-4">
                <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                    <div>
                        <span class="text-muted extra-small text-uppercase fw-bold">Subject</span>
                        <h5 class="fw-bold text-dark mb-0 mt-1">{{ $contact->subject }}</h5>
                    </div>
                    <a href="mailto:{{ $contact->email }}?subject=Re: {{ rawurlencode($contact->subject) }}" class="btn btn-success btn-sm rounded-3 px-3">
                        <i class="fas fa-reply me-1"></i> Reply via Email
                    </a>
                </div>

                <div class="mb-4">
                    <span class="text-muted extra-small text-uppercase fw-bold d-block mb-2">Message Body</span>
                    <div class="p-3 bg-light rounded-3 border text-dark" style="white-space: pre-wrap; font-size: 0.93rem; line-height: 1.7;">{{ $contact->message }}</div>
                </div>

                <div class="row g-3 text-muted small border-top pt-3">
                    <div class="col-sm-6">
                        <strong>Sender IP:</strong> {{ $contact->ip_address ?? 'N/A' }}
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <strong>Submitted:</strong> {{ $contact->created_at->diffForHumans() }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Sender Info & Status Management --}}
        <div class="col-lg-4">
            <div class="inv-table-card p-4 mb-4">
                <h6 class="fw-bold text-dark mb-3"><i class="fas fa-user text-primary me-2"></i> Sender Information</h6>
                <div class="mb-3">
                    <label class="text-muted extra-small text-uppercase fw-bold">Full Name</label>
                    <div class="fw-semibold text-dark">{{ $contact->name }}</div>
                </div>
                <div class="mb-3">
                    <label class="text-muted extra-small text-uppercase fw-bold">Email Address</label>
                    <div><a href="mailto:{{ $contact->email }}" class="text-primary text-decoration-none">{{ $contact->email }}</a></div>
                </div>
                <div class="mb-3">
                    <label class="text-muted extra-small text-uppercase fw-bold">Phone Number</label>
                    <div>
                        @if($contact->phone)
                            <a href="tel:{{ $contact->phone }}" class="text-dark text-decoration-none">{{ $contact->phone }}</a>
                        @else
                            <span class="text-muted">Not provided</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="inv-table-card p-4">
                <h6 class="fw-bold text-dark mb-3"><i class="fas fa-tag text-success me-2"></i> Update Status & Notes</h6>
                <form action="{{ route('contacts.update', $contact->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="unread" {{ $contact->status === 'unread' ? 'selected' : '' }}>Unread</option>
                            <option value="read" {{ $contact->status === 'read' ? 'selected' : '' }}>Read</option>
                            <option value="replied" {{ $contact->status === 'replied' ? 'selected' : '' }}>Replied</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Internal Notes</label>
                        <textarea name="notes" class="form-control form-control-sm" rows="3" placeholder="Add private admin notes about this inquiry...">{{ old('notes', $contact->notes) }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm w-100 rounded-3">
                        <i class="fas fa-floppy-disk me-1"></i> Save Changes
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

</x-backend-layout>
