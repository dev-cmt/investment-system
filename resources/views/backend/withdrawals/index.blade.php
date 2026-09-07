<x-backend-layout>
    <div class="inv-page-container">

        <!-- ── 1. Hero Header ───────────────────────────────────────────── -->
        <div class="inv-hero-header">
            <div class="d-flex align-items-center gap-3">
                <div class="inv-title-badge">
                    <i class="fas fa-money-bill-transfer"></i>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2">
                        <h4 class="fw-bold mb-0 text-dark">
                            {{ $isAdmin ? 'Withdrawal Requests Management' : 'My Withdrawal Requests' }}
                        </h4>
                        <span class="badge bg-light text-dark border rounded-pill px-2.5 py-1 extra-small fw-semibold">
                            {{ $withdrawals->total() }} Total
                        </span>
                    </div>
                    <p class="text-muted small mb-0 mt-0.5">
                        {{ $isAdmin ? 'Review and process investor profit/capital payout requests.' : 'Request and track your investment profit payouts.' }}
                    </p>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-primary btn-sm rounded-3 px-3.5 py-2 fw-semibold d-inline-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#requestWithdrawalModal">
                    <i class="fas fa-plus"></i>
                    <span>Request Payout</span>
                </button>
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

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4 d-flex align-items-center gap-2" role="alert">
                <i class="fas fa-exclamation-circle fs-5 text-danger"></i>
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- ── 2. Top Summary / KPI Cards ──────────────────────────────── -->
        <div class="inv-stats-grid">
            <div class="inv-stat-card stat-teal">
                <div>
                    <div class="inv-stat-label">Total Requested</div>
                    <div class="inv-stat-val text-teal-800">&#2547;{{ number_format($totalRequested, 2) }}</div>
                    <div class="inv-stat-sub">Lifetime withdrawal applications</div>
                </div>
                <div class="inv-stat-icon text-teal-600" style="background: #ccfbf1; color: #0d9488;">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
            </div>

            <div class="inv-stat-card stat-emerald">
                <div>
                    <div class="inv-stat-label">Approved &amp; Disbursed</div>
                    <div class="inv-stat-val text-success">&#2547;{{ number_format($totalApproved, 2) }}</div>
                    <div class="inv-stat-sub">Successfully paid to investors</div>
                </div>
                <div class="inv-stat-icon text-success" style="background: #ecfdf5; color: #10b981;">
                    <i class="fas fa-circle-check"></i>
                </div>
            </div>

            <div class="inv-stat-card stat-amber">
                <div>
                    <div class="inv-stat-label">Pending Requests</div>
                    <div class="inv-stat-val text-warning">{{ $pendingCount }} Request(s)</div>
                    <div class="inv-stat-sub">Awaiting verification &amp; transfer</div>
                </div>
                <div class="inv-stat-icon text-warning" style="background: #fffbeb; color: #f59e0b;">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>

        <!-- ── 3. Filters & Search Bar ──────────────────────────────────── -->
        <div class="inv-filter-card">
            <form method="GET" action="{{ route('withdrawals.index') }}" class="row g-2 align-items-center">
                <div class="col-lg-5 col-md-6 col-12">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted ps-3"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-1" placeholder="Search withdrawal #, account, or method..." value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-12">
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>⏳ Pending Review</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>✅ Approved</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>🏆 Completed (Paid)</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>❌ Rejected</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>🚫 Cancelled</option>
                    </select>
                </div>

                <div class="col-lg-3 col-md-12 col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-dark btn-sm rounded-3 w-100 fw-semibold py-2 d-inline-flex align-items-center justify-content-center gap-1">
                        <i class="fas fa-filter"></i>
                        <span>Filter</span>
                    </button>
                    @if(request()->anyFilled(['search', 'status']))
                        <a href="{{ route('withdrawals.index') }}" class="btn btn-outline-secondary btn-sm rounded-3 py-2 px-2.5" title="Clear Filters">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- ── 4. Mobile View (d-md-none) ───────────────────────────────── -->
        <div class="d-md-none">
            @forelse($withdrawals as $wd)
                @php
                    $badgeConfig = match($wd->status) {
                        'approved'  => ['class' => 'bg-info-subtle text-info border border-info-subtle', 'icon' => 'fas fa-check'],
                        'completed' => ['class' => 'bg-success-subtle text-success border border-success-subtle', 'icon' => 'fas fa-circle-check'],
                        'pending'   => ['class' => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle', 'icon' => 'fas fa-clock'],
                        'rejected'  => ['class' => 'bg-danger-subtle text-danger border border-danger-subtle', 'icon' => 'fas fa-times-circle'],
                        'cancelled' => ['class' => 'bg-secondary-subtle text-secondary border border-secondary-subtle', 'icon' => 'fas fa-ban'],
                        default     => ['class' => 'bg-light text-dark border', 'icon' => 'fas fa-circle'],
                    };
                @endphp
                <div class="inv-mobile-card">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <strong class="d-block text-dark small fw-bold font-monospace">{{ $wd->withdrawal_number }}</strong>
                            <span class="text-muted extra-small"><i class="fas fa-building-columns text-primary me-1"></i>{{ $wd->payment_method }}</span>
                        </div>
                        <span class="inv-badge-pill {{ $badgeConfig['class'] }}">
                            <i class="{{ $badgeConfig['icon'] }}"></i> {{ $wd->status }}
                        </span>
                    </div>

                    @if($isAdmin && $wd->user)
                        <div class="p-2 bg-light rounded-3 border mb-2 extra-small">
                            <span class="text-muted d-block">Investor:</span>
                            <strong class="text-dark">{{ $wd->user->name }}</strong> ({{ $wd->user->email }})
                        </div>
                    @endif

                    <div class="inv-mobile-card-grid">
                        <div>
                            <span class="text-muted d-block extra-small">Amount</span>
                            <strong class="text-success fs-6">&#2547;{{ number_format($wd->amount, 2) }}</strong>
                        </div>
                        <div>
                            <span class="text-muted d-block extra-small">Request Date</span>
                            <strong class="text-dark">{{ $wd->created_at->format('d M Y') }}</strong>
                        </div>
                        <div class="col-span-2">
                            <span class="text-muted d-block extra-small">Account Details</span>
                            <strong class="text-dark">{{ $wd->account_number }}</strong>
                            @if($wd->account_name)
                                <span class="text-muted">({{ $wd->account_name }})</span>
                            @endif
                        </div>
                    </div>

                    @if($wd->user_note || $wd->admin_note)
                        <div class="gap-2 bg-light rounded-3 border mb-3 extra-small">
                            @if($wd->user_note)
                                <div><strong class="text-muted">User Note:</strong> {{ $wd->user_note }}</div>
                            @endif
                            @if($wd->admin_note)
                                <div class="mt-1"><strong class="text-primary">Admin Note:</strong> {{ $wd->admin_note }}</div>
                            @endif
                        </div>
                    @endif

                    <div class="d-flex gap-2 border-top pt-2">
                        @if($isAdmin)
                            <button type="button" class="btn btn-sm btn-outline-primary w-100 rounded-3 update-wd-btn fw-semibold py-1.5"
                                data-bs-toggle="modal"
                                data-bs-target="#adminStatusModal"
                                data-id="{{ $wd->id }}"
                                data-number="{{ $wd->withdrawal_number }}"
                                data-status="{{ $wd->status }}"
                                data-note="{{ $wd->admin_note }}">
                                <i class="fas fa-sliders me-1"></i> Update Status
                            </button>
                        @else
                            @if($wd->status == 'pending')
                                <form action="{{ route('withdrawals.update', $wd->id) }}" method="POST" class="w-100" onsubmit="return confirm('Cancel this withdrawal request?');">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit" class="btn btn-sm btn-outline-danger w-100 rounded-3 fw-semibold py-1.5">
                                        <i class="fas fa-ban me-1"></i> Cancel Request
                                    </button>
                                </form>
                            @else
                                <span class="text-muted extra-small w-100 text-center py-1">No actions available</span>
                            @endif
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-5 bg-white rounded-4 shadow-sm text-muted">
                    <i class="fas fa-inbox fa-3x mb-3 text-muted opacity-25"></i>
                    <p class="mb-0 fw-semibold">No withdrawal requests found.</p>
                </div>
            @endforelse
        </div>

        <!-- ── 5. Desktop Table View (d-none d-md-block) ────────────────── -->
        <div class="inv-table-card d-none d-md-block">
            <div class="table-responsive">
                <table class="table inv-table align-middle">
                    <thead>
                        <tr>
                            <th class="ps-4" style="min-width: 170px;">Withdrawal #</th>
                            @if($isAdmin)
                                <th style="min-width: 200px;">Investor / Client</th>
                            @endif
                            <th style="min-width: 220px;">Method &amp; Account</th>
                            <th style="min-width: 150px;">Amount</th>
                            <th style="min-width: 150px;">Request Date</th>
                            <th style="min-width: 130px;">Status</th>
                            <th class="text-end pe-4" style="min-width: 130px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($withdrawals as $wd)
                            @php
                                $badgeConfig = match($wd->status) {
                                    'approved'  => ['class' => 'bg-info-subtle text-info border border-info-subtle', 'icon' => 'fas fa-check'],
                                    'completed' => ['class' => 'bg-success-subtle text-success border border-success-subtle', 'icon' => 'fas fa-circle-check'],
                                    'pending'   => ['class' => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle', 'icon' => 'fas fa-clock'],
                                    'rejected'  => ['class' => 'bg-danger-subtle text-danger border border-danger-subtle', 'icon' => 'fas fa-times-circle'],
                                    'cancelled' => ['class' => 'bg-secondary-subtle text-secondary border border-secondary-subtle', 'icon' => 'fas fa-ban'],
                                    default     => ['class' => 'bg-light text-dark border', 'icon' => 'fas fa-circle'],
                                };
                            @endphp
                            <tr>
                                {{-- 1. Number --}}
                                <td class="ps-4">
                                    <span class="badge bg-light text-dark border font-monospace px-2.5 py-1 extra-small fw-bold">
                                        {{ $wd->withdrawal_number }}
                                    </span>
                                    @if($wd->user_note)
                                        <div class="text-muted extra-small text-truncate mt-1" style="max-width: 160px;" title="{{ $wd->user_note }}">
                                            <i class="far fa-comment-dots me-1"></i>{{ $wd->user_note }}
                                        </div>
                                    @endif
                                </td>

                                {{-- 2. Investor --}}
                                @if($isAdmin)
                                    <td>
                                        <div style="min-width: 0;">
                                            <div class="fw-bold text-dark text-truncate mb-0" style="font-size: 0.88rem;">
                                                {{ $wd->user->name ?? 'Deleted User' }}
                                            </div>
                                            <div class="text-muted extra-small">
                                                {{ $wd->user->email ?? '' }}
                                            </div>
                                        </div>
                                    </td>
                                @endif

                                {{-- 3. Method & Account --}}
                                <td>
                                    <div>
                                        <div class="fw-semibold text-dark mb-0.5">
                                            <i class="fas fa-building-columns text-primary me-1"></i>{{ $wd->payment_method }}
                                        </div>
                                        <div class="text-muted extra-small">
                                            Acc: <strong class="text-dark">{{ $wd->account_number }}</strong>
                                            @if($wd->account_name)
                                                ({{ $wd->account_name }})
                                            @endif
                                        </div>
                                        @if($wd->bank_name)
                                            <div class="text-muted extra-small">
                                                {{ $wd->bank_name }} @if($wd->branch_name) &bull; {{ $wd->branch_name }} @endif
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                {{-- 4. Amount --}}
                                <td>
                                    <span class="fw-bold text-dark fs-6">&#2547;{{ number_format($wd->amount, 2) }}</span>
                                </td>

                                {{-- 5. Date --}}
                                <td>
                                    <div class="text-dark small">
                                        <i class="far fa-calendar-alt text-muted me-1"></i>{{ $wd->created_at->format('d M, Y') }}
                                    </div>
                                    <div class="text-muted extra-small">
                                        {{ $wd->created_at->format('h:i A') }}
                                    </div>
                                </td>

                                {{-- 6. Status --}}
                                <td>
                                    <span class="inv-badge-pill {{ $badgeConfig['class'] }}">
                                        <i class="{{ $badgeConfig['icon'] }}"></i> {{ $wd->status }}
                                    </span>
                                </td>

                                {{-- 7. Actions --}}
                                <td class="text-end pe-4">
                                    @if($isAdmin)
                                        <button type="button" class="btn btn-sm btn-outline-primary rounded-3 px-2.5 py-1 update-wd-btn extra-small fw-semibold"
                                            data-bs-toggle="modal"
                                            data-bs-target="#adminStatusModal"
                                            data-id="{{ $wd->id }}"
                                            data-number="{{ $wd->withdrawal_number }}"
                                            data-status="{{ $wd->status }}"
                                            data-note="{{ $wd->admin_note }}"
                                            title="Update Status">
                                            <i class="fas fa-sliders me-1"></i> Update
                                        </button>
                                    @else
                                        @if($wd->status == 'pending')
                                            <form action="{{ route('withdrawals.update', $wd->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Cancel this withdrawal request?');">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="cancelled">
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-3 px-2.5 py-1 extra-small fw-semibold" title="Cancel Request">
                                                    <i class="fas fa-ban me-1"></i> Cancel
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted extra-small">N/A</span>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $isAdmin ? 7 : 6 }}" class="text-center py-5 text-muted">
                                    <div class="py-4">
                                        <div class="mb-3">
                                            <i class="fas fa-money-bill-transfer fa-3x text-muted opacity-25"></i>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-1">No withdrawal requests found</h6>
                                        <p class="text-muted small mb-0">Submit a payout request to withdraw your profit balance.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ── 6. Pagination ───────────────────────────────────────────── -->
        @if($withdrawals->hasPages())
            <div class="mt-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="text-muted small">
                    Showing {{ $withdrawals->firstItem() ?? 0 }} to {{ $withdrawals->lastItem() ?? 0 }} of {{ $withdrawals->total() }} requests
                </div>
                <div>
                    {{ $withdrawals->links() }}
                </div>
            </div>
        @endif

    </div>

    <!-- ── 7. Request Withdrawal Modal ─────────────────────────────────── -->
    <div class="modal fade" id="requestWithdrawalModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header inv-modal-header text-white">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-hand-holding-dollar fs-5"></i>
                        <h5 class="modal-title fw-bold mb-0">Request Payout / Withdrawal</h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('withdrawals.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            @if($isAdmin)
                                <div class="col-12">
                                    <label class="form-label fw-semibold small text-dark">Select Client / Investor <span class="text-danger">*</span></label>
                                    <select name="user_id" class="form-select" required>
                                        @foreach($users as $u)
                                            <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <div class="col-md-6 col-12">
                                <label class="form-label fw-semibold small text-dark">Withdrawal Amount (&#2547;) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 fw-bold">&#2547;</span>
                                    <input type="number" name="amount" class="form-control" placeholder="e.g. 5000" min="10" step="1" required>
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <label class="form-label fw-semibold small text-dark">Payment Method <span class="text-danger">*</span></label>
                                <select name="payment_method" class="form-select" required>
                                    <option value="bKash">bKash (Mobile Banking)</option>
                                    <option value="Nagad">Nagad (Mobile Banking)</option>
                                    <option value="Rocket">Rocket (Mobile Banking)</option>
                                    <option value="Bank Transfer">Bank Transfer (Commercial Bank)</option>
                                </select>
                            </div>

                            <div class="col-md-6 col-12">
                                <label class="form-label fw-semibold small text-dark">Account / Phone Number <span class="text-danger">*</span></label>
                                <input type="text" name="account_number" class="form-control" placeholder="017xxxxxxxx or Bank Account #" required>
                            </div>

                            <div class="col-md-6 col-12">
                                <label class="form-label fw-semibold small text-dark">Account Holder Name</label>
                                <input type="text" name="account_name" class="form-control" placeholder="e.g. John Doe">
                            </div>

                            <div class="col-md-6 col-12">
                                <label class="form-label fw-semibold small text-dark">Bank Name (If Bank Transfer)</label>
                                <input type="text" name="bank_name" class="form-control" placeholder="e.g. Islami Bank, City Bank">
                            </div>

                            <div class="col-md-6 col-12">
                                <label class="form-label fw-semibold small text-dark">Branch Name</label>
                                <input type="text" name="branch_name" class="form-control" placeholder="e.g. Dhanmondi Branch">
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold small text-dark">Instructions / Note (Optional)</label>
                                <textarea name="user_note" class="form-control" rows="2" placeholder="Any specific payout instructions or routing remarks..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light py-3 px-4 border-top">
                        <button type="button" class="btn btn-secondary btn-sm rounded-3 px-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm rounded-3 px-4 fw-semibold shadow-sm">Submit Payout Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ── 8. Admin Status Update Modal ────────────────────────────────── -->
    @if($isAdmin)
        <div class="modal fade" id="adminStatusModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="modal-header inv-modal-header text-white">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-sliders fs-5"></i>
                            <h5 class="modal-title fw-bold mb-0">Update Withdrawal Status</h5>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="adminStatusForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <label class="form-label fw-semibold small text-dark">Withdrawal Number</label>
                                <input type="text" id="wd_number_display" class="form-control bg-light font-monospace" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold small text-dark">Status <span class="text-danger">*</span></label>
                                <select name="status" id="wd_status" class="form-select" required>
                                    <option value="pending">⏳ Pending Review</option>
                                    <option value="approved">✅ Approved</option>
                                    <option value="completed">🏆 Completed (Disbursed)</option>
                                    <option value="rejected">❌ Rejected</option>
                                    <option value="cancelled">🚫 Cancelled</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold small text-dark">Admin Note / Transaction Reference</label>
                                <textarea name="admin_note" id="wd_admin_note" class="form-control" rows="3" placeholder="Enter bank reference, bKash Trx ID, or note for user..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer bg-light py-3 px-4 border-top">
                            <button type="button" class="btn btn-secondary btn-sm rounded-3 px-3" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary btn-sm rounded-3 px-4 fw-semibold shadow-sm">Save Status</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var updateBtns = document.querySelectorAll('.update-wd-btn');
            updateBtns.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var id     = this.getAttribute('data-id');
                    var number = this.getAttribute('data-number');
                    var status = this.getAttribute('data-status');
                    var note   = this.getAttribute('data-note');

                    var form = document.getElementById('adminStatusForm');
                    if (form) {
                        form.action = '{{ url("withdrawals") }}/' + id;
                        document.getElementById('wd_number_display').value = number;
                        document.getElementById('wd_status').value = status;
                        document.getElementById('wd_admin_note').value = note || '';
                    }
                });
            });
        });
    </script>
    @endpush
</x-backend-layout>
