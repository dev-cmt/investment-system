<x-backend-layout>

<div class="inv-page-container">

    {{-- ── Header ── --}}
    <div class="inv-hero-header">
        <div class="d-flex align-items-center gap-3">
            <div class="inv-title-badge">
                <i class="fas fa-envelope-open-text"></i>
            </div>
            <div>
                <div class="d-flex align-items-center gap-2">
                    <h4 class="fw-bold mb-0 text-dark">Contact Messages</h4>
                    @if($unreadCount > 0)
                        <span class="badge bg-danger rounded-pill px-2.5 py-1 extra-small fw-semibold">
                            {{ $unreadCount }} Unread
                        </span>
                    @endif
                </div>
                <p class="text-muted small mb-0 mt-0.5">View and manage inquiries received from the public Contact Us page.</p>
            </div>
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

    {{-- ── Filter Bar ── --}}
    <div class="inv-table-card mb-4 p-3">
        <form method="GET" action="{{ route('contacts.index') }}" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control form-control-sm border-start-0" placeholder="Search by name, email, phone, subject, or message..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="all" {{ request('status') === 'all' || !request('status') ? 'selected' : '' }}>All Statuses</option>
                    <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Unread Only</option>
                    <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Read</option>
                    <option value="replied" {{ request('status') === 'replied' ? 'selected' : '' }}>Replied</option>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2 justify-content-md-end">
                <button type="submit" class="btn btn-primary btn-sm rounded-3 px-3"><i class="fas fa-filter me-1"></i> Filter</button>
                @if(request('search') || request('status'))
                    <a href="{{ route('contacts.index') }}" class="btn btn-outline-secondary btn-sm rounded-3 px-3">Reset</a>
                @endif
            </div>
        </form>
    </div>

    {{-- ── Messages Table ── --}}
    <div class="inv-table-card p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4" style="width: 60px;">#</th>
                        <th>Sender</th>
                        <th>Contact Details</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Received Date</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contacts as $contact)
                        <tr class="{{ $contact->status === 'unread' ? 'fw-bold table-light' : '' }}">
                            <td class="ps-4 text-muted small">{{ $loop->iteration + ($contacts->currentPage() - 1) * $contacts->perPage() }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-sm rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width:34px;height:34px;background:linear-gradient(135deg,#1a9e4f,#15803d);font-size:0.85rem;">
                                        {{ strtoupper(substr($contact->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="text-dark">{{ $contact->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="small text-dark"><i class="fas fa-envelope text-muted me-1"></i> {{ $contact->email }}</div>
                                @if($contact->phone)
                                    <div class="extra-small text-muted"><i class="fas fa-phone text-muted me-1"></i> {{ $contact->phone }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border me-1">{{ $contact->subject }}</span>
                                <div class="text-muted extra-small text-truncate" style="max-width: 260px;">{{ Str::limit($contact->message, 60) }}</div>
                            </td>
                            <td>
                                @if($contact->status === 'unread')
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2.5 py-1 extra-small">Unread</span>
                                @elseif($contact->status === 'read')
                                    <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-2.5 py-1 extra-small">Read</span>
                                @else
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 extra-small">Replied</span>
                                @endif
                            </td>
                            <td class="small text-muted">
                                {{ $contact->created_at->format('d M Y, h:i A') }}
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('contacts.show', $contact->id) }}" class="btn btn-outline-primary btn-sm rounded-2 px-2.5 py-1 extra-small" title="View Message">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <form action="{{ route('contacts.destroy', $contact->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this message?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-2 px-2 py-1 extra-small" title="Delete">
                                            <i class="fas fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-envelope-open fs-1 text-secondary opacity-50 mb-3"></i>
                                    <h6>No contact messages found</h6>
                                    <p class="small mb-0">Messages submitted via the public Contact Us page will appear here.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($contacts->hasPages())
            <div class="p-3 border-top">
                {{ $contacts->links() }}
            </div>
        @endif
    </div>

</div>

</x-backend-layout>
