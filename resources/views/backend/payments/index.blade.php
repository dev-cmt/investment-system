<x-backend-layout>
    <div class="inv-page-container">

        <!-- ── 1. Hero Header ───────────────────────────────────────────── -->
        <div class="inv-hero-header">
            <div class="d-flex align-items-center gap-3">
                <div class="inv-title-badge">
                    <i class="fas fa-receipt"></i>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2">
                        <h4 class="fw-bold mb-0 text-dark">Payment Transactions</h4>
                        <span class="badge bg-light text-dark border rounded-pill px-2.5 py-1 extra-small fw-semibold">
                            {{ $payments->total() }} Total
                        </span>
                    </div>
                    <p class="text-muted small mb-0 mt-0.5">Record and manage client payment deposits, verification slips, and payout disbursements.</p>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-primary btn-sm rounded-3 px-3.5 py-2 fw-semibold d-inline-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#{{ $isAdmin ? 'createPaymentModal' : 'userPaymentModal' }}">
                    <i class="fas fa-plus"></i>
                    <span>Add Payment</span>
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
        @php
            $totalApprovedSum = $payments->where('status', 'approved')->sum('paid_amount');
            $totalPendingSum = $payments->where('status', 'pending')->sum('paid_amount');
            $pendingCount = $payments->where('status', 'pending')->count();
            $approvedCount = $payments->where('status', 'approved')->count();
        @endphp
        <div class="inv-stats-grid">
            <div class="inv-stat-card stat-emerald">
                <div>
                    <div class="inv-stat-label">Approved Capital</div>
                    <div class="inv-stat-val text-success">&#2547;{{ number_format($totalApprovedSum) }}</div>
                    <div class="inv-stat-sub">{{ $approvedCount }} confirmed transactions</div>
                </div>
                <div class="inv-stat-icon text-success" style="background: #ecfdf5; color: #10b981;">
                    <i class="fas fa-circle-check"></i>
                </div>
            </div>

            <div class="inv-stat-card stat-amber">
                <div>
                    <div class="inv-stat-label">Pending Verification</div>
                    <div class="inv-stat-val text-warning">&#2547;{{ number_format($totalPendingSum) }}</div>
                    <div class="inv-stat-sub">{{ $pendingCount }} transactions awaiting review</div>
                </div>
                <div class="inv-stat-icon text-warning" style="background: #fffbeb; color: #f59e0b;">
                    <i class="fas fa-clock"></i>
                </div>
            </div>

            <div class="inv-stat-card stat-teal">
                <div>
                    <div class="inv-stat-label">Total Records</div>
                    <div class="inv-stat-val text-teal-800">{{ $payments->total() }}</div>
                    <div class="inv-stat-sub">Across all payment channels</div>
                </div>
                <div class="inv-stat-icon text-teal-600" style="background: #ccfbf1; color: #0d9488;">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
            </div>

            <div class="inv-stat-card stat-blue">
                <div>
                    <div class="inv-stat-label">Settlement Rate</div>
                    <div class="inv-stat-val text-primary">
                        @if($payments->count() > 0)
                            {{ number_format(($approvedCount / max(1, $payments->count())) * 100, 0) }}%
                        @else
                            100%
                        @endif
                    </div>
                    <div class="inv-stat-sub">Successful deposit ratio</div>
                </div>
                <div class="inv-stat-icon text-primary" style="background: #eff6ff; color: #3b82f6;">
                    <i class="fas fa-chart-simple"></i>
                </div>
            </div>
        </div>

        <!-- ── 3. Filters & Search Bar ──────────────────────────────────── -->
        <div class="inv-filter-card">
            <form method="GET" action="{{ route('payments.index') }}" class="row g-2 align-items-center">
                <div class="col-lg-5 col-md-6 col-12">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted ps-3"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-1" placeholder="Search payment #, transaction ID, or message..." value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-12">
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>✅ Approved</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
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
                        <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary btn-sm rounded-3 py-2 px-2.5" title="Clear Filters">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- ── 4. Mobile View (d-md-none) ───────────────────────────────── -->
        <div class="d-md-none">
            @forelse($payments as $payment)
                @php
                    $badgeConfig = match($payment->status) {
                        'approved'  => ['class' => 'bg-success-subtle text-success border border-success-subtle', 'icon' => 'fas fa-check-circle'],
                        'pending'   => ['class' => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle', 'icon' => 'fas fa-clock'],
                        'rejected'  => ['class' => 'bg-danger-subtle text-danger border border-danger-subtle', 'icon' => 'fas fa-times-circle'],
                        'cancelled' => ['class' => 'bg-secondary-subtle text-secondary border border-secondary-subtle', 'icon' => 'fas fa-ban'],
                        default     => ['class' => 'bg-light text-dark border', 'icon' => 'fas fa-circle'],
                    };
                @endphp
                <div class="inv-mobile-card">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <strong class="d-block text-dark small fw-bold font-monospace">{{ $payment->payment_number }}</strong>
                            <span class="text-muted extra-small">{{ $payment->post->title ?? 'Investment Opportunity' }}</span>
                        </div>
                        <span class="inv-badge-pill {{ $badgeConfig['class'] }}">
                            <i class="{{ $badgeConfig['icon'] }}"></i> {{ $payment->status }}
                        </span>
                    </div>

                    <div class="inv-mobile-card-grid">
                        <div>
                            <span class="text-muted d-block extra-small">Paid Amount</span>
                            <strong class="text-success fs-6">&#2547;{{ number_format($payment->paid_amount, 2) }}</strong>
                        </div>
                        <div>
                            <span class="text-muted d-block extra-small">Payment Date</span>
                            <strong class="text-dark">{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') : 'N/A' }}</strong>
                        </div>
                        <div class="col-span-2">
                            <span class="text-muted d-block extra-small">Transaction ID</span>
                            <span class="badge bg-light text-dark border font-monospace">{{ $payment->transaction_id ?: 'N/A' }}</span>
                        </div>
                    </div>

                    @if($payment->slip)
                        <div class="mb-3">
                            <a href="{{ asset($payment->slip) }}" target="_blank" class="btn btn-sm btn-light border text-dark w-100 rounded-3 extra-small fw-semibold py-1.5">
                                <i class="fas fa-file-lines me-1 text-primary"></i> View Payment Slip / Receipt
                            </a>
                        </div>
                    @endif

                    @if($isAdmin)
                        <div class="d-flex flex-wrap gap-2 border-top pt-2">
                            @if($payment->status === 'pending')
                                <form action="{{ route('payments.approve', $payment->id) }}" method="POST" class="flex-grow-1" onsubmit="return confirm('Are you sure you want to approve this payment record?');">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success w-100 rounded-3 fw-semibold py-1.5">
                                        <i class="fas fa-check me-1"></i> Approve
                                    </button>
                                </form>
                                <form action="{{ route('payments.reject', $payment->id) }}" method="POST" class="flex-grow-1" onsubmit="return confirm('Are you sure you want to reject this payment record?');">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger w-100 rounded-3 fw-semibold py-1.5">
                                        <i class="fas fa-times me-1"></i> Reject
                                    </button>
                                </form>
                            @endif

                            @if($payment->status !== 'approved')
                                <form action="{{ route('payments.destroy', $payment->id) }}" method="POST" class="w-100" onsubmit="return confirm('Are you sure you want to delete this payment record?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger w-100 rounded-3 fw-semibold py-1.5">
                                        <i class="fas fa-trash me-1"></i> Delete
                                    </button>
                                </form>
                            @else
                                <div class="w-100 text-center py-1">
                                    <span class="badge bg-light text-muted border px-2.5 py-1 extra-small">
                                        <i class="fas fa-lock me-1"></i> Approved (Locked)
                                    </span>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-5 bg-white rounded-4 shadow-sm text-muted">
                    <i class="fas fa-inbox fa-3x mb-3 text-muted opacity-25"></i>
                    <p class="mb-0 fw-semibold">No payment records found.</p>
                </div>
            @endforelse
        </div>

        <!-- ── 5. Desktop Table View (d-none d-md-block) ────────────────── -->
        <div class="inv-table-card d-none d-md-block">
            <div class="table-responsive">
                <table class="table inv-table align-middle">
                    <thead>
                        <tr>
                            <th class="ps-4" style="min-width: 170px;">Payment #</th>
                            <th style="min-width: 210px;">Investment Post / Client</th>
                            <th style="min-width: 150px;">Paid Amount</th>
                            <th style="min-width: 140px;">Date</th>
                            <th style="min-width: 160px;">Transaction Ref</th>
                            <th style="min-width: 110px;">Slip</th>
                            <th style="min-width: 120px;">Status</th>
                            <th class="text-end pe-4" style="min-width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            @php
                                $badgeConfig = match($payment->status) {
                                    'approved'  => ['class' => 'bg-success-subtle text-success border border-success-subtle', 'icon' => 'fas fa-check-circle'],
                                    'pending'   => ['class' => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle', 'icon' => 'fas fa-clock'],
                                    'rejected'  => ['class' => 'bg-danger-subtle text-danger border border-danger-subtle', 'icon' => 'fas fa-times-circle'],
                                    'cancelled' => ['class' => 'bg-secondary-subtle text-secondary border border-secondary-subtle', 'icon' => 'fas fa-ban'],
                                    default     => ['class' => 'bg-light text-dark border', 'icon' => 'fas fa-circle'],
                                };
                            @endphp
                            <tr>
                                {{-- 1. Payment # --}}
                                <td class="ps-4">
                                    <span class="badge bg-light text-dark border font-monospace px-2.5 py-1 extra-small fw-bold">
                                        {{ $payment->payment_number }}
                                    </span>
                                </td>

                                {{-- 2. Post & Client --}}
                                <td>
                                    <div style="min-width: 0;">
                                        <div class="fw-bold text-dark text-truncate mb-0.5" style="font-size: 0.88rem; max-width: 220px;" title="{{ $payment->post->title ?? 'N/A' }}">
                                            {{ $payment->post->title ?? 'Investment Opportunity' }}
                                        </div>
                                        @if($isAdmin && $payment->member)
                                            <div class="text-muted extra-small">
                                                <i class="far fa-user me-1 opacity-75"></i>{{ $payment->member->name }}
                                                <span class="opacity-75">({{ $payment->member->email }})</span>
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                {{-- 3. Paid Amount --}}
                                <td>
                                    <span class="fw-bold text-success fs-6">&#2547;{{ number_format($payment->paid_amount, 2) }}</span>
                                </td>

                                {{-- 4. Date --}}
                                <td>
                                    <div class="text-dark small">
                                        <i class="far fa-calendar-alt text-muted me-1"></i>{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('d M, Y') : 'N/A' }}
                                    </div>
                                </td>

                                {{-- 5. Transaction Ref --}}
                                <td>
                                    @if($payment->transaction_id)
                                        <span class="badge bg-light text-dark border font-monospace px-2 py-1 extra-small">
                                            {{ $payment->transaction_id }}
                                        </span>
                                    @else
                                        <span class="text-muted extra-small">N/A</span>
                                    @endif
                                </td>

                                {{-- 6. Slip --}}
                                <td>
                                    @if($payment->slip)
                                        <a href="{{ asset($payment->slip) }}" target="_blank" class="btn btn-sm btn-light border text-primary inv-action-btn" title="View Payment Slip">
                                            <i class="fas fa-file-lines"></i>
                                        </a>
                                    @else
                                        <span class="text-muted extra-small"><i class="fas fa-minus opacity-50"></i></span>
                                    @endif
                                </td>

                                {{-- 7. Status --}}
                                <td>
                                    <span class="inv-badge-pill {{ $badgeConfig['class'] }}">
                                        <i class="{{ $badgeConfig['icon'] }}"></i> {{ $payment->status }}
                                    </span>
                                </td>

                                {{-- 8. Actions --}}
                                <td class="text-end pe-4">
                                    @if($isAdmin)
                                        <div class="d-inline-flex align-items-center gap-1 justify-content-end">
                                            @if($payment->status === 'pending')
                                                <form action="{{ route('payments.approve', $payment->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to approve this payment record?');">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-success inv-action-btn" title="Approve Payment">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('payments.reject', $payment->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to reject this payment record?');">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger inv-action-btn" title="Reject Payment">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            @if($payment->status !== 'approved')
                                                <form action="{{ route('payments.destroy', $payment->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this payment record?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger inv-action-btn" title="Delete Payment">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="badge bg-light text-muted border px-2 py-1 extra-small" title="Approved payments are locked and cannot be deleted">
                                                    <i class="fas fa-lock me-1"></i> Locked
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted extra-small">View only</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <div class="py-4">
                                        <div class="mb-3">
                                            <i class="fas fa-receipt fa-3x text-muted opacity-25"></i>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-1">No payment transactions found</h6>
                                        <p class="text-muted small mb-0">Try changing your search query or record a new deposit.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ── 6. Pagination ───────────────────────────────────────────── -->
        @if($payments->hasPages())
            <div class="mt-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="text-muted small">
                    Showing {{ $payments->firstItem() ?? 0 }} to {{ $payments->lastItem() ?? 0 }} of {{ $payments->total() }} records
                </div>
                <div>
                    {{ $payments->links() }}
                </div>
            </div>
        @endif

    </div>

    @if($isAdmin)
    <!-- ── 7. Admin Create Payment Modal ──────────────────────────────── -->
    <div class="modal fade" id="createPaymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header inv-modal-header text-white">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-plus-circle fs-5"></i>
                        <h5 class="modal-title fw-bold mb-0">Record Payment Transaction</h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('payments.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold small text-dark">Investment Opportunity <span class="text-danger">*</span></label>
                                <select id="create_post_filter" name="investment_post_id" class="form-select">
                                    <option value="">— Select Opportunity to Filter Investors —</option>
                                    @foreach($posts as $p)
                                        <option value="{{ $p->id }}">{{ $p->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <input type="hidden" name="investment_id" id="create_investment_id">

                            <div class="col-12">
                                <label class="form-label fw-semibold small text-dark">Client / Investor <span class="text-danger">*</span></label>
                                <select name="member_id" id="create_member_select" class="form-select" required>
                                    <option value="" disabled selected>Select opportunity first to load investors...</option>
                                </select>
                            </div>

                            <div class="col-md-6 col-12">
                                <label class="form-label fw-semibold small text-dark">Paid Amount (&#2547;) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 fw-bold">&#2547;</span>
                                    <input type="number" name="paid_amount" id="create_paid_amount" class="form-control" placeholder="e.g. 50000" min="1" step="0.01" required>
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <label class="form-label fw-semibold small text-dark">Payment Date <span class="text-danger">*</span></label>
                                <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>

                            <div class="col-md-6 col-12">
                                <label class="form-label fw-semibold small text-dark">Transaction ID / Reference</label>
                                <input type="text" name="transaction_id" class="form-control" placeholder="e.g. TXN98765432">
                            </div>

                            <div class="col-md-6 col-12">
                                <label class="form-label fw-semibold small text-dark">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select" required>
                                    <option value="approved" selected>✅ Approved</option>
                                    <option value="pending">⏳ Pending</option>
                                    <option value="rejected">❌ Rejected</option>
                                    <option value="cancelled">🚫 Cancelled</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold small text-dark">Payment Slip / Receipt</label>
                                <input type="file" name="slip" class="form-control" accept="image/jpeg,image/png,image/webp,application/pdf">
                                <small class="text-muted extra-small">JPG, PNG, WEBP or PDF — max 5MB.</small>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold small text-dark">Note / Remarks</label>
                                <textarea name="message" class="form-control" rows="2" placeholder="Optional payment note..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light py-3 px-4 border-top">
                        <button type="button" class="btn btn-secondary btn-sm rounded-3 px-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm rounded-3 px-4 fw-semibold shadow-sm">Save Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @else
    <!-- ── 8. Investor User Payment Modal ─────────────────────────────── -->
    <div class="modal fade" id="userPaymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header inv-modal-header bg-pay text-white">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-credit-card fs-5"></i>
                        <h5 class="modal-title fw-bold mb-0">Submit Payment Transaction</h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('investments.pay') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="investment_id" id="payment_investment_id">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-dark">Investment Opportunity <span class="text-danger">*</span></label>
                            <select id="payment_investment_select" class="form-select" required>
                                <option value="">Select your investment opportunity</option>
                                @foreach($userInvestments as $investment)
                                    @php
                                        $invPaid = (float) $investment->payments->where('status', 'approved')->sum('paid_amount');
                                        $invTotal = (float) ($investment->investment_amount ?? 0);
                                        $invDue = max(0, $invTotal - $invPaid);
                                        $hasPendingSlip = $investment->payments->where('status', 'pending')->isNotEmpty();
                                    @endphp
                                    <option value="{{ $investment->id }}"
                                        data-amount="{{ $invDue > 0 ? $invDue : $invTotal }}"
                                        data-total="{{ $invTotal }}"
                                        data-paid="{{ $invPaid }}"
                                        data-due="{{ $invDue }}"
                                        {{ ($hasPendingSlip || $invDue <= 0) ? 'disabled' : '' }}>
                                        {{ $investment->post->title ?? 'Investment #' . $investment->id }} — Invested: &#2547;{{ number_format($invTotal) }} | Due: &#2547;{{ number_format($invDue) }}
                                        {{ $hasPendingSlip ? ' (Slip Verifying)' : ($invDue <= 0 ? ' (Fully Paid)' : '') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6 col-12">
                                <label class="form-label fw-semibold small text-dark">Payment Amount (&#2547;) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 fw-bold">&#2547;</span>
                                    <input type="number" name="paid_amount" id="payment_paid_amount" class="form-control" min="1" step="any" required placeholder="Enter amount">
                                </div>
                                <div id="userPayIndexMaxWarning" class="text-danger extra-small mt-1 d-none fw-semibold">
                                    <i class="fas fa-exclamation-circle me-1"></i> Cannot exceed due amount of ৳<span id="userPayIndexMaxDueText">0</span>.
                                </div>
                                <small class="text-muted extra-small d-block mt-1">Full or partial payment accepted (max: due amount).</small>
                            </div>
                            <div class="col-md-6 col-12">
                                <label class="form-label fw-semibold small text-dark">Payment Date <span class="text-danger">*</span></label>
                                <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold small text-dark">Transaction ID</label>
                                <input type="text" name="transaction_id" class="form-control" placeholder="e.g. TXN123456789">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold small text-dark">Payment Slip / Receipt <span class="text-danger">*</span></label>
                                <input type="file" name="slip" class="form-control" accept="image/jpeg,image/png,image/webp,application/pdf" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold small text-dark">Note (Optional)</label>
                                <textarea name="message" class="form-control" rows="2" placeholder="Any notes about this transfer..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light py-3 px-4 border-top">
                        <button type="button" class="btn btn-secondary btn-sm rounded-3 px-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success btn-sm rounded-3 px-4 fw-semibold shadow-sm">
                            <i class="fas fa-paper-plane me-1"></i> Submit Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    @push('scripts')
    <script>
        const postsWithInvestors = @json($postsWithInvestors ?? []);

        document.addEventListener('DOMContentLoaded', function() {
            var postFilter   = document.getElementById('create_post_filter');
            var memberSelect = document.getElementById('create_member_select');
            var paidAmountEl = document.getElementById('create_paid_amount');
            var invIdEl      = document.getElementById('create_investment_id');

            if (postFilter) postFilter.addEventListener('change', function() {
                var postId    = this.value;
                var investors = postsWithInvestors[postId] || [];
                memberSelect.innerHTML = '';
                if (invIdEl) invIdEl.value = '';
                if (investors.length === 0) {
                    memberSelect.innerHTML = '<option value="" disabled selected>No investors found for this opportunity</option>';
                } else {
                    var defaultOpt = document.createElement('option');
                    defaultOpt.value = '';
                    defaultOpt.disabled = true;
                    defaultOpt.selected = true;
                    defaultOpt.textContent = 'Select Investor...';
                    memberSelect.appendChild(defaultOpt);
                    investors.forEach(function(inv) {
                        var opt = document.createElement('option');
                        opt.value        = inv.user_id;
                        var due = inv.due_amount !== undefined ? inv.due_amount : inv.amount;
                        opt.textContent  = inv.user_name + ' (' + inv.user_email + ') — Due: ৳' + parseFloat(due).toLocaleString() + ' (Total: ৳' + parseFloat(inv.amount).toLocaleString() + ')';
                        opt.dataset.amount       = due > 0 ? due : inv.amount;
                        opt.dataset.investmentId = inv.investment_id;
                        memberSelect.appendChild(opt);
                    });
                }
            });

            if (memberSelect) memberSelect.addEventListener('change', function() {
                var selected = this.options[this.selectedIndex];
                if (selected && selected.dataset.amount) {
                    if (paidAmountEl) paidAmountEl.value = Math.round(parseFloat(selected.dataset.amount));
                    if (invIdEl) invIdEl.value = selected.dataset.investmentId || '';
                }
            });

            var currentUserPayDue = 0;
            var userPayAmountInput = document.getElementById('payment_paid_amount');
            var userPayWarning = document.getElementById('userPayIndexMaxWarning');
            var userPayMaxDueText = document.getElementById('userPayIndexMaxDueText');

            if (userPayAmountInput) {
                userPayAmountInput.addEventListener('input', function() {
                    var val = parseFloat(this.value);
                    if (isNaN(val) || val <= 0) {
                        this.setCustomValidity('Please enter a valid amount (minimum ৳1)');
                        if (userPayWarning) userPayWarning.classList.add('d-none');
                    } else if (currentUserPayDue > 0 && val > currentUserPayDue) {
                        if (userPayWarning) userPayWarning.classList.remove('d-none');
                        this.setCustomValidity('Payment amount cannot exceed ৳' + Math.round(currentUserPayDue));
                    } else {
                        if (userPayWarning) userPayWarning.classList.add('d-none');
                        this.setCustomValidity('');
                    }
                });
            }

            var investmentSelect = document.getElementById('payment_investment_select');
            if (investmentSelect) investmentSelect.addEventListener('change', function() {
                var invInput = document.getElementById('payment_investment_id');
                var paidInput = document.getElementById('payment_paid_amount');
                if (invInput) invInput.value = this.value;
                var selected = this.options[this.selectedIndex];
                if (selected && selected.dataset.due) {
                    var due = parseFloat(selected.dataset.due) || 0;
                    currentUserPayDue = due;
                    if (paidInput) {
                        paidInput.value = due > 0 ? Math.round(due) : (selected.dataset.amount ? Math.round(selected.dataset.amount) : '');
                        paidInput.max   = due > 0 ? Math.round(due) : '';
                        paidInput.setCustomValidity('');
                    }
                    if (userPayMaxDueText) userPayMaxDueText.textContent = Math.round(due).toLocaleString('en-BD');
                    if (userPayWarning) userPayWarning.classList.add('d-none');
                }
            });
        });
    </script>
    @endpush
</x-backend-layout>
