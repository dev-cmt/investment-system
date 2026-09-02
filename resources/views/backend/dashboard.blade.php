<x-backend-layout>

<style>
/* ── Dashboard layout ───────────────────────── */
.db-page {
    padding: 24px 24px 48px;
    max-width: 1400px;
    margin: 0 auto;
}

/* Page Header */
.db-page-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 22px; flex-wrap: wrap; gap: 12px;
}
.db-page-header h1 {
    font-size: 1.25rem; font-weight: 800; color: #0f172a; margin: 0;
}
.db-page-header p { font-size: 0.8rem; color: #64748b; margin: 3px 0 0; }

/* ── PANEL (white card) ─────────────────────── */
.db-panel {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(15, 23, 42, 0.03);
}
.db-panel-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 20px;
    border-bottom: 1px solid #f1f5f9;
}
.db-panel-head-title { font-size: 0.92rem; font-weight: 700; color: #0f172a; }

/* Mini stat card */
.mini-stat {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 20px;
    display: flex; align-items: center; gap: 16px;
    transition: all 0.2s ease;
    box-shadow: 0 2px 10px rgba(15, 23, 42, 0.03);
}
.mini-stat:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08); }
.mini-stat-icon {
    width: 48px; height: 48px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.15rem; flex-shrink: 0;
}
.mini-stat-label { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; margin-bottom: 4px; }
.mini-stat-value { font-size: 1.4rem; font-weight: 800; color: #0f172a; line-height: 1; }
.mini-stat-sub { font-size: 0.73rem; color: #64748b; margin-top: 5px; }

/* Status Pill */
.inv-status-pill {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 3px 10px; border-radius: 20px;
    font-size: 0.68rem; font-weight: 700; text-transform: uppercase;
    white-space: nowrap;
}
.pill-green { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
.pill-blue  { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
.pill-amber { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
.pill-purple{ background: #faf5ff; color: #6d28d9; border: 1px solid #e9d5ff; }
.pill-gray  { background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }

.inv-empty {
    padding: 36px;
    text-align: center;
    color: #94a3b8;
    font-size: 0.85rem;
}
.inv-empty i { font-size: 2rem; display: block; margin-bottom: 10px; color: #cbd5e1; }

@media (max-width: 768px) {
    .db-page { padding: 16px 12px 40px; }
}
</style>

<div class="db-page">

    {{-- Flash Notifications --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm mb-4" role="alert">
            <i class="fas fa-check-circle me-1.5"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Page Header --}}
    <div class="db-page-header">
        <div>
            <div class="d-flex align-items-center gap-2">
                <h1 class="mb-0">Dashboard</h1>
                @if($isAdmin)
                    <span class="badge bg-primary rounded-pill px-3 py-1 extra-small text-uppercase">Admin Panel</span>
                @else
                    <span class="badge bg-success rounded-pill px-3 py-1 extra-small text-uppercase">Investor Portfolio</span>
                @endif
            </div>
            <p>Welcome back, <strong>{{ Auth::user()->name }}</strong> ({{ Auth::user()->email }}). Here is your activity summary.</p>
        </div>

        <div class="d-flex align-items-center gap-2">
            @if(!$isAdmin)
                <a href="{{ route('withdrawals.index') }}" class="btn btn-outline-primary btn-sm rounded-3 fw-semibold px-3 py-2">
                    <i class="fas fa-money-bill-transfer me-1.5"></i> Request Withdrawal
                </a>
            @endif
            <a href="{{ route('home') }}#opportunities" class="btn btn-success btn-sm rounded-3 fw-bold px-3 py-2">
                <i class="fas fa-plus me-1.5"></i> Explore Opportunities
            </a>
        </div>
    </div>

    @if($isAdmin)
        <!-- ========================================================
             ADMIN SYSTEM OVERVIEW DASHBOARD
             ======================================================== -->
        <div class="row g-3 mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="mini-stat">
                    <div class="mini-stat-icon" style="background:#e8f5ee;color:#1a9e4f;"><i class="fas fa-chart-pie"></i></div>
                    <div>
                        <div class="mini-stat-label">Total Platform Investment</div>
                        <div class="mini-stat-value text-success">&#2547;{{ number_format($systemStats['total_invested'], 2) }}</div>
                        <div class="mini-stat-sub">Across all posts</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="mini-stat">
                    <div class="mini-stat-icon" style="background:#eff6ff;color:#2563eb;"><i class="fas fa-users"></i></div>
                    <div>
                        <div class="mini-stat-label">Registered Investors</div>
                        <div class="mini-stat-value text-primary">{{ number_format($systemStats['total_investors']) }}</div>
                        <div class="mini-stat-sub">Active members</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="mini-stat">
                    <div class="mini-stat-icon" style="background:#fffbeb;color:#d97706;"><i class="fas fa-clock"></i></div>
                    <div>
                        <div class="mini-stat-label">Pending Withdrawals</div>
                        <div class="mini-stat-value text-warning">{{ number_format($systemStats['pending_withdrawals']) }}</div>
                        <div class="mini-stat-sub">Requires action</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="mini-stat">
                    <div class="mini-stat-icon" style="background:#faf5ff;color:#7c3aed;"><i class="fas fa-boxes-stacked"></i></div>
                    <div>
                        <div class="mini-stat-label">Active Opportunities</div>
                        <div class="mini-stat-value" style="color:#7c3aed;">{{ number_format($systemStats['active_posts']) }}</div>
                        <div class="mini-stat-sub">Open for bids</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ADMIN QUICK MANAGEMENT ROW -->
        <div class="row g-4 mb-4">
            <!-- Recent Investments -->
            <div class="col-lg-6">
                <div class="db-panel h-100">
                    <div class="db-panel-head">
                        <span class="db-panel-head-title"><i class="fas fa-hand-holding-dollar me-2 text-primary"></i>Recent Platform Investments</span>
                        <a href="{{ route('investments.index') }}" class="btn btn-sm btn-outline-secondary rounded-3 extra-small fw-semibold">View All</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 extra-small">
                            <thead class="bg-light text-muted text-uppercase">
                                <tr>
                                    <th class="ps-3">Investor</th>
                                    <th>Product</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentInvestments as $rinv)
                                    <tr>
                                        <td class="ps-3 fw-semibold text-dark">{{ $rinv->user->name ?? 'User' }}</td>
                                        <td class="text-truncate" style="max-width: 140px;">{{ $rinv->post->title ?? 'Post' }}</td>
                                        <td class="fw-bold text-success">&#2547;{{ number_format($rinv->amount) }}</td>
                                        <td><span class="inv-status-pill pill-green">{{ $rinv->status }}</span></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center py-4 text-muted">No investments recorded yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Recent Withdrawals -->
            <div class="col-lg-6">
                <div class="db-panel h-100">
                    <div class="db-panel-head">
                        <span class="db-panel-head-title"><i class="fas fa-money-bill-transfer me-2 text-warning"></i>Recent Payout / Withdrawal Requests</span>
                        <a href="{{ route('withdrawals.index') }}" class="btn btn-sm btn-outline-secondary rounded-3 extra-small fw-semibold">Manage Withdrawals</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 extra-small">
                            <thead class="bg-light text-muted text-uppercase">
                                <tr>
                                    <th class="ps-3">Investor</th>
                                    <th>Method</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentWithdrawals as $rwd)
                                    <tr>
                                        <td class="ps-3 fw-semibold text-dark">{{ $rwd->user->name ?? 'User' }}</td>
                                        <td>{{ $rwd->payment_method }}</td>
                                        <td class="fw-bold text-dark">&#2547;{{ number_format($rwd->amount) }}</td>
                                        <td>
                                            <span class="badge {{ $rwd->status_badge_class }} rounded-pill px-2 py-0.5 extra-small">
                                                {{ $rwd->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center py-4 text-muted">No withdrawal requests found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    @else
        <!-- ========================================================
             INVESTOR DASHBOARD PORTFOLIO
             ======================================================== -->
        <div class="row g-3 mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="mini-stat">
                    <div class="mini-stat-icon" style="background:#e8f5ee;color:#1a9e4f;"><i class="fas fa-wallet"></i></div>
                    <div>
                        <div class="mini-stat-label">Total Invested</div>
                        <div class="mini-stat-value text-success">&#2547;{{ number_format($totalInvested, 2) }}</div>
                        <div class="mini-stat-sub">{{ $userInvestments->count() }} active investment(s)</div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="mini-stat">
                    <div class="mini-stat-icon" style="background:#eff6ff;color:#2563eb;"><i class="fas fa-sack-dollar"></i></div>
                    <div>
                        <div class="mini-stat-label">Expected Profit</div>
                        <div class="mini-stat-value text-primary">&#2547;{{ number_format($totalExpectedProfit, 2) }}</div>
                        <div class="mini-stat-sub">Estimated returns</div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="mini-stat">
                    <div class="mini-stat-icon" style="background:#faf5ff;color:#7c3aed;"><i class="fas fa-receipt"></i></div>
                    <div>
                        <div class="mini-stat-label">Approved Payments</div>
                        <div class="mini-stat-value" style="color:#7c3aed;">&#2547;{{ number_format($userPayments->where('status','approved')->sum('paid_amount'), 2) }}</div>
                        <div class="mini-stat-sub">{{ $userPayments->count() }} transaction(s)</div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="mini-stat">
                    <div class="mini-stat-icon" style="background:#fffbeb;color:#d97706;"><i class="fas fa-money-bill-transfer"></i></div>
                    <div>
                        <div class="mini-stat-label">Total Withdrawn</div>
                        <div class="mini-stat-value text-warning">&#2547;{{ number_format($totalWithdrawn, 2) }}</div>
                        <div class="mini-stat-sub">{{ $userWithdrawals->count() }} payout request(s)</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- INVESTOR PORTFOLIO TABLE & PAYOUTS -->
        <div class="row g-4 mb-4">
            <!-- Investments Table -->
            <div class="col-lg-8">
                <div class="db-panel h-100">
                    <div class="db-panel-head">
                        <span class="db-panel-head-title"><i class="fas fa-hand-holding-dollar me-2 text-success"></i>My Investment Portfolio</span>
                        <a href="{{ route('investments.index') }}" class="btn btn-sm btn-outline-primary rounded-3 extra-small fw-semibold">View All</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 extra-small">
                            <thead class="bg-light text-muted text-uppercase">
                                <tr>
                                    <th class="ps-4">Product Post</th>
                                    <th>Invested Amount</th>
                                    <th>Expected Profit</th>
                                    <th>Quantity Share</th>
                                    <th>Investment Status</th>
                                    <th>Payment Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($userInvestments as $inv)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold text-dark mb-0">{{ $inv->post->title ?? 'Investment Opportunity' }}</div>
                                            <small class="text-muted">{{ $inv->created_at->format('d M Y') }}</small>
                                        </td>
                                        <td class="fw-bold text-dark">&#2547;{{ number_format($inv->amount, 2) }}</td>
                                        <td class="fw-bold text-success">&#2547;{{ number_format($inv->expected_profit, 2) }}</td>
                                        <td><span class="badge bg-light text-dark border">{{ number_format($inv->calculated_quantity_share) }} pcs</span></td>
                                        <td>
                                            @php
                                                $statusClass = match($inv->status) {
                                                    'active' => 'pill-green',
                                                    'completed' => 'pill-blue',
                                                    default => 'pill-amber',
                                                };
                                            @endphp
                                            <span class="inv-status-pill {{ $statusClass }}">{{ $inv->status }}</span>
                                        </td>
                                        <td>
                                            @if($inv->status === 'active' || $inv->status === 'approved')
                                                @php $latestPay = $inv->latestPayment; @endphp
                                                @if($latestPay && $latestPay->status === 'approved')
                                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-2 fw-semibold">
                                                        <i class="fas fa-check-circle me-1"></i> Paid & Approved
                                                    </span>
                                                @elseif($latestPay && $latestPay->status === 'pending')
                                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1 rounded-2 fw-semibold">
                                                        <i class="fas fa-clock me-1"></i> Slip Submitted (Pending)
                                                    </span>
                                                @else
                                                    <button type="button" class="btn btn-sm btn-success py-1 px-2.5 rounded-3 fw-bold user-pay-btn"
                                                        data-bs-toggle="modal" data-bs-target="#userPaymentModal"
                                                        data-investment-id="{{ $inv->id }}"
                                                        data-post-title="{{ $inv->post->title ?? 'Opportunity' }}"
                                                        data-amount="{{ $inv->amount }}">
                                                        <i class="fas fa-credit-card me-1"></i> Pay Now
                                                    </button>
                                                @endif
                                            @elseif($inv->status === 'pending')
                                                <span class="text-muted extra-small"><i class="fas fa-hourglass-half me-1"></i> Pending Approval</span>
                                            @else
                                                <span class="badge bg-light text-dark border px-2 py-1 rounded-2">{{ ucfirst($inv->status) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="inv-empty">
                                            <i class="fas fa-hand-holding-dollar"></i>
                                            You haven't placed any investments yet.
                                            <br>
                                            <a href="{{ route('home') }}#opportunities" class="btn btn-success btn-sm rounded-3 mt-2 fw-bold">Browse Active Opportunities &rarr;</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Recent Withdrawals Summary Card -->
            <div class="col-lg-4">
                <div class="db-panel h-100">
                    <div class="db-panel-head">
                        <span class="db-panel-head-title"><i class="fas fa-money-bill-transfer me-2 text-warning"></i>My Withdrawal History</span>
                        <a href="{{ route('withdrawals.index') }}" class="btn btn-sm btn-outline-warning rounded-3 extra-small fw-semibold">New Request</a>
                    </div>
                    <div class="p-3">
                        <ul class="list-group list-group-flush extra-small">
                            @forelse($userWithdrawals->take(5) as $uwd)
                                <li class="list-group-item px-0 py-2.5 d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong class="d-block text-dark">{{ $uwd->withdrawal_number }}</strong>
                                        <small class="text-muted">{{ $uwd->payment_method }} &bull; {{ $uwd->account_number }}</small>
                                    </div>
                                    <div class="text-end">
                                        <strong class="d-block text-dark">&#2547;{{ number_format($uwd->amount) }}</strong>
                                        <span class="badge {{ $uwd->status_badge_class }} rounded-pill px-2 py-0.5" style="font-size:0.65rem;">
                                            {{ $uwd->status }}
                                        </span>
                                    </div>
                                </li>
                            @empty
                                <div class="text-center py-4 text-muted">
                                    <i class="fas fa-money-bill-transfer fa-2x mb-2 text-secondary opacity-50"></i>
                                    <p class="mb-0 extra-small">No withdrawal requests yet.</p>
                                </div>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>

{{-- User Payment Modal --}}
<div class="modal fade" id="userPaymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-success text-white py-3">
                <h5 class="modal-title fw-bold fs-6"><i class="fas fa-credit-card me-2"></i>Submit Payment</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('investments.pay') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="investment_id" id="payInvestmentId">
                <div class="modal-body p-4">
                    <div class="alert alert-success-subtle border border-success-subtle rounded-3 mb-3 py-2 px-3 small" id="payInvestmentSummary">
                        <i class="fas fa-info-circle me-1 text-success"></i>
                        <span id="payInvestmentInfo"></span>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Paid Amount (&#2547;) <span class="text-danger">*</span></label>
                            <input type="number" name="paid_amount" id="payAmount" class="form-control form-control-sm" step="0.01" min="1" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Payment Date <span class="text-danger">*</span></label>
                            <input type="date" name="payment_date" class="form-control form-control-sm" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Transaction ID</label>
                            <input type="text" name="transaction_id" class="form-control form-control-sm" placeholder="e.g. TXN123456789">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Payment Slip / Receipt <span class="text-danger">*</span></label>
                            <input type="file" name="slip" class="form-control form-control-sm" accept="image/jpeg,image/png,image/webp,application/pdf" required>
                            <small class="text-muted extra-small">Upload your payment receipt (JPG, PNG, WEBP, or PDF — max 5MB).</small>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Note (Optional)</label>
                            <textarea name="message" class="form-control form-control-sm" rows="2" placeholder="Any notes about this payment..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2 px-4 border-top">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success btn-sm px-4 fw-semibold"><i class="fas fa-paper-plane me-1"></i>Submit Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.querySelectorAll('.user-pay-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var invId    = this.getAttribute('data-investment-id');
            var title    = this.getAttribute('data-post-title');
            var amount   = this.getAttribute('data-amount');
            document.getElementById('payInvestmentId').value = invId;
            document.getElementById('payAmount').value       = parseFloat(amount).toFixed(2);
            document.getElementById('payInvestmentInfo').textContent =
                'Investment: ' + title + '  |  Amount: ৳' + parseFloat(amount).toLocaleString('en-BD', {minimumFractionDigits: 2});
        });
    });
</script>
@endpush

</x-backend-layout>
