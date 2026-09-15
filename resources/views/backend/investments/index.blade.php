<x-backend-layout>


    <div class="inv-page-container">

        <!-- ── 1. Hero Header ───────────────────────────────────────────── -->
        <div class="inv-hero-header">
            <div class="d-flex align-items-center gap-3">
                <div class="inv-title-badge">
                    <i class="fas fa-hand-holding-dollar"></i>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2">
                        <h4 class="fw-bold mb-0 text-dark">Investments Management</h4>
                        <span class="badge bg-light text-dark border rounded-pill px-2.5 py-1 extra-small fw-semibold">
                            {{ $investments->total() }} Total
                        </span>
                    </div>
                    <p class="text-muted small mb-0 mt-0.5">Track and manage client investment portfolios, returns, and manual allocations.</p>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-primary btn-sm rounded-3 px-3.5 py-2 fw-semibold d-inline-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#createInvestmentModal">
                    <i class="fas fa-plus"></i>
                    <span>{{ ($isAdmin ?? false) ? 'Add New Investment' : 'Place New Investment' }}</span>
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
            $totalInvestedSum = $investments->sum('investment_amount');
            $totalExpectedProfitSum = $investments->sum('expected_profit');
            $totalPaidSum = $investments->sum(function($inv) {
                return $inv->payments->where('status', 'approved')->sum('paid_amount');
            });
            $pendingCount = $investments->where('status', 'pending')->count();
        @endphp
        <div class="inv-stats-grid">
            <div class="inv-stat-card stat-teal">
                <div>
                    <div class="inv-stat-label">Total Capital</div>
                    <div class="inv-stat-val text-teal-800">&#2547;{{ number_format($totalInvestedSum) }}</div>
                    <div class="inv-stat-sub">Across {{ $investments->count() }} active page investments</div>
                </div>
                <div class="inv-stat-icon bg-teal-50 text-teal-600" style="background: #ccfbf1; color: #0d9488;">
                    <i class="fas fa-sack-dollar"></i>
                </div>
            </div>

            <div class="inv-stat-card stat-emerald">
                <div>
                    <div class="inv-stat-label">Expected Profits</div>
                    <div class="inv-stat-val text-success">+&#2547;{{ number_format($totalExpectedProfitSum) }}</div>
                    <div class="inv-stat-sub">Projected return for investors</div>
                </div>
                <div class="inv-stat-icon text-success" style="background: #ecfdf5; color: #10b981;">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
            </div>

            <div class="inv-stat-card stat-blue">
                <div>
                    <div class="inv-stat-label">Collected Payments</div>
                    <div class="inv-stat-val text-primary">&#2547;{{ number_format($totalPaidSum) }}</div>
                    <div class="inv-stat-sub">
                        @if($totalInvestedSum > 0)
                            {{ number_format(($totalPaidSum / $totalInvestedSum) * 100, 1) }}% of target funded
                        @else
                            0% funded
                        @endif
                    </div>
                </div>
                <div class="inv-stat-icon text-primary" style="background: #eff6ff; color: #3b82f6;">
                    <i class="fas fa-wallet"></i>
                </div>
            </div>

            <div class="inv-stat-card stat-amber">
                <div>
                    <div class="inv-stat-label">Pending Approval</div>
                    <div class="inv-stat-val text-warning">{{ $pendingCount }}</div>
                    <div class="inv-stat-sub">Awaiting admin review</div>
                </div>
                <div class="inv-stat-icon text-warning" style="background: #fffbeb; color: #f59e0b;">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>

        <!-- ── 3. Filters & Search Bar ──────────────────────────────────── -->
        <div class="inv-filter-card">
            <form method="GET" action="{{ route('investments.index') }}" class="row g-2 align-items-center">
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted ps-3"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-1" placeholder="Search investor name, email, or product..." value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-12">
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>⏳ Pending Approval</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>✅ Active</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>🏆 Completed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                        <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>🔄 Refunded</option>
                    </select>
                </div>

                <div class="col-lg-3 col-md-6 col-12">
                    <select name="investment_post_id" class="form-select">
                        <option value="">All Product Opportunities</option>
                        @foreach($posts as $filterPost)
                            <option value="{{ $filterPost->id }}" {{ request('investment_post_id') == $filterPost->id ? 'selected' : '' }}>
                                {{ $filterPost->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-2 col-md-6 col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-dark btn-sm rounded-3 w-100 fw-semibold py-2 d-inline-flex align-items-center justify-content-center gap-1">
                        <i class="fas fa-filter"></i>
                        <span>Filter</span>
                    </button>
                    @if(request()->anyFilled(['search', 'status', 'investment_post_id', 'post_id']))
                        <a href="{{ route('investments.index') }}" class="btn btn-outline-secondary btn-sm rounded-3 py-2 px-2.5" title="Clear Filters">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- ── 4. Mobile View (d-md-none) ───────────────────────────────── -->
        <div class="d-md-none">
            @forelse($investments as $investment)
                @php
                    $paidAmount = $investment->payments->where('status', 'approved')->sum('paid_amount');
                    $dueAmount  = max(0, $investment->investment_amount - $paidAmount);
                    $badgeConfig = match($investment->status) {
                        'pending'   => ['class' => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle', 'icon' => 'fas fa-clock'],
                        'active'    => ['class' => 'bg-success-subtle text-success border border-success-subtle', 'icon' => 'fas fa-check-circle'],
                        'completed' => ['class' => 'bg-primary-subtle text-primary border border-primary-subtle', 'icon' => 'fas fa-award'],
                        'cancelled' => ['class' => 'bg-danger-subtle text-danger border border-danger-subtle', 'icon' => 'fas fa-times-circle'],
                        'refunded'  => ['class' => 'bg-secondary-subtle text-secondary border border-secondary-subtle', 'icon' => 'fas fa-rotate-left'],
                        default     => ['class' => 'bg-light text-dark border', 'icon' => 'fas fa-circle'],
                    };
                    $postImage = $investment->post->gallery_image_urls[0] ?? asset('asset/images/earbuds.jpg');
                @endphp

                <div class="inv-mobile-card">
                    <!-- Investor & Status -->
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <div class="inv-avatar">
                                {{ strtoupper(substr($investment->user->name ?? 'U', 0, 2)) }}
                            </div>
                            <div>
                                <strong class="d-block text-dark small fw-bold">{{ $investment->user->name ?? 'Deleted User' }}</strong>
                                <span class="text-muted extra-small">{{ $investment->user->email ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <span class="inv-badge-pill {{ $badgeConfig['class'] }}">
                            <i class="{{ $badgeConfig['icon'] }}"></i> {{ $investment->status }}
                        </span>
                    </div>

                    <!-- Product Snippet -->
                    <div class="d-flex gap-2 gap-2 bg-light rounded-3 border mb-2 align-items-center">
                        <img src="{{ $postImage }}" alt="" class="inv-prod-thumb" style="width: 44px; height: 44px;">
                        <div class="overflow-hidden">
                            <span class="text-muted extra-small d-block">Product Opportunity</span>
                            <strong class="text-dark small text-truncate d-block">{{ $investment->post->title ?? 'N/A' }}</strong>
                            <div class="d-flex align-items-center gap-2 mt-0.5">
                                <span class="text-muted extra-small">Target: &#2547;{{ number_format($investment->post->target_amount ?? 0) }}</span>
                                @if($investment->post && $investment->post->expected_import_days)
                                    <span class="badge bg-light text-muted border inv-badge-sm"><i class="fas fa-truck-fast me-1 text-primary"></i>{{ $investment->post->expected_import_days }}d</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Post Specs Breakdown -->
                    <div class="p-2 bg-light-subtle border rounded-3 mb-2">
                        <div class="text-uppercase fw-bold text-muted extra-small mb-1.5" style="letter-spacing: 0.5px;">Product Opportunity Specs</div>
                        <div class="row g-1 small">
                            <div class="col-6"><span class="text-muted extra-small">Unit Cost:</span> <strong class="text-dark extra-small">&#2547;{{ number_format($investment->post->unit_cost ?? 0, 2) }}</strong></div>
                            <div class="col-6"><span class="text-muted extra-small">Total Qty:</span> <strong class="text-dark extra-small">{{ number_format($investment->post->total_quantity ?? 0) }} pcs</strong></div>
                            <div class="col-6"><span class="text-muted extra-small">Target Amount:</span> <strong class="text-dark extra-small">&#2547;{{ number_format($investment->post->target_amount ?? 0) }}</strong></div>
                            <div class="col-6"><span class="text-muted extra-small">Profit / pc:</span> <strong class="text-success extra-small">&#2547;{{ number_format($investment->post->profit_per_unit ?? 0, 2) }}</strong></div>
                        </div>
                    </div>

                    <!-- Investment & Payment 2x2 Grid -->
                    <div class="inv-mobile-card-grid">
                        <div>
                            <span class="text-muted d-block extra-small">Invested Amount</span>
                            <strong class="text-dark fs-6">&#2547;{{ number_format($investment->investment_amount) }}</strong>
                        </div>
                        <div>
                            <span class="text-muted d-block extra-small">Quantity Share</span>
                            <strong class="text-primary fs-6">{{ number_format($investment->calculated_quantity_share) }} pcs</strong>
                        </div>
                        <div>
                            <span class="text-muted d-block extra-small">Expected Profit</span>
                            <strong class="text-success fs-6">+&#2547;{{ number_format($investment->expected_profit) }}</strong>
                        </div>
                        <div>
                            <span class="text-muted d-block extra-small">Paid / Due</span>
                            <strong class="{{ $paidAmount >= $investment->investment_amount ? 'text-success' : 'text-warning' }}">
                                &#2547;{{ number_format($paidAmount) }} / &#2547;{{ number_format($dueAmount) }}
                            </strong>
                        </div>
                    </div>

                    <!-- Payment Bar -->
                    @php
                        $payPercent = $investment->investment_amount > 0 ? min(100, round(($paidAmount / $investment->investment_amount) * 100)) : 0;
                    @endphp
                    <div class="mb-3">
                        <div class="d-flex justify-content-between extra-small text-muted mb-1">
                            <span>Payment Status ({{ $payPercent }}%)</span>
                            <span>{{ $paidAmount >= $investment->investment_amount ? 'Fully Paid' : 'Due: ৳' . number_format($dueAmount) }}</span>
                        </div>
                        <div class="inv-pay-progress">
                            <div class="inv-pay-progress-bar {{ $paidAmount >= $investment->investment_amount ? 'bg-success' : 'bg-warning' }}" style="width: {{ $payPercent }}%;"></div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex flex-wrap gap-2 border-top pt-2">
                        @if(($isAdmin ?? false) && $investment->status === 'pending')
                            <form action="{{ route('investments.approve', $investment->id) }}" method="POST" class="flex-grow-1" onsubmit="return confirm('Approve this investment bid? This will make it active and generate a payment record.');">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success w-100 rounded-3 fw-semibold py-1.5">
                                    <i class="fas fa-check me-1"></i> Approve
                                </button>
                            </form>
                            <form action="{{ route('investments.reject', $investment->id) }}" method="POST" class="flex-grow-1" onsubmit="return confirm('Reject this investment bid?');">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger w-100 rounded-3 fw-semibold py-1.5">
                                    <i class="fas fa-times me-1"></i> Reject
                                </button>
                            </form>
                        @endif

                        @if(in_array($investment->status, ['active', 'approved', 'sold', 'completed']))
                            @php
                                $hasPendingSlip = $investment->payments->where('status', 'pending')->isNotEmpty();
                            @endphp
                            @if($hasPendingSlip)
                                <div class="w-100 text-center">
                                    <span class="badge bg-info-subtle text-info border border-info-subtle px-3 py-2 rounded-3 fw-semibold d-block">
                                        <i class="fas fa-clock me-1"></i> Slip Awaiting Verification
                                    </span>
                                </div>
                            @elseif($dueAmount > 0)
                                <button type="button" class="btn btn-sm btn-success flex-grow-1 rounded-3 fw-semibold py-1.5 inv-pay-btn"
                                    data-bs-toggle="modal" data-bs-target="#investPaymentModal"
                                    data-investment-id="{{ $investment->id }}"
                                    data-post-title="{{ $investment->post->title ?? 'Opportunity' }}"
                                    data-total-amount="{{ $investment->investment_amount }}"
                                    data-paid-amount="{{ $paidAmount }}"
                                    data-due-amount="{{ $dueAmount }}"
                                    data-investor="{{ $investment->user->name ?? '' }}">
                                    <i class="fas fa-credit-card me-1"></i> {{ $paidAmount > 0 ? 'Pay Due (৳' . number_format($dueAmount) . ')' : 'Pay Now' }}
                                </button>
                            @else
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-3 fw-semibold flex-grow-1 text-center">
                                    <i class="fas fa-check-circle me-1"></i> Fully Paid
                                </span>
                            @endif
                        @endif

                        @if(!in_array($investment->status, ['active', 'approved', 'sold', 'completed']))
                            <form action="{{ route('investments.destroy', $investment->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this investment?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-3 px-3 py-1.5 fw-semibold" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-5 bg-white rounded-4 shadow-sm text-muted">
                    <i class="fas fa-inbox fa-3x mb-3 text-muted opacity-25"></i>
                    <p class="mb-0 fw-semibold">No investments found.</p>
                </div>
            @endforelse
        </div>

        <!-- ── 5. Desktop Table View (d-none d-md-block) ────────────────── -->
        <div class="inv-table-card d-none d-md-block">
            <div class="table-responsive">
                <table class="table inv-table align-middle">
                    <thead>
                        <tr>
                            <th class="ps-4" style="min-width: 210px;">Investor / Client</th>
                            <th style="min-width: 210px;">Product Opportunity</th>
                            <th style="min-width: 180px;">Post Specs</th>
                            <th style="min-width: 180px;">Investment Breakdown</th>
                            <th style="min-width: 180px;">Payment Status</th>
                            <th style="min-width: 120px;">Status</th>
                            <th class="text-end pe-4" style="min-width: 150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($investments as $investment)
                            @php
                                $paidAmount = $investment->payments->where('status', 'approved')->sum('paid_amount');
                                $dueAmount  = max(0, $investment->investment_amount - $paidAmount);
                                $hasPendingPayment = $investment->payments->where('status', 'pending')->isNotEmpty();
                                $postImage  = $investment->post->gallery_image_urls[0] ?? asset('asset/images/earbuds.jpg');
                                $badgeConfig = match($investment->status) {
                                    'pending'   => ['class' => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle', 'icon' => 'fas fa-clock'],
                                    'active'    => ['class' => 'bg-success-subtle text-success border border-success-subtle', 'icon' => 'fas fa-check-circle'],
                                    'completed' => ['class' => 'bg-primary-subtle text-primary border border-primary-subtle', 'icon' => 'fas fa-award'],
                                    'cancelled' => ['class' => 'bg-danger-subtle text-danger border border-danger-subtle', 'icon' => 'fas fa-times-circle'],
                                    'refunded'  => ['class' => 'bg-secondary-subtle text-secondary border border-secondary-subtle', 'icon' => 'fas fa-rotate-left'],
                                    default     => ['class' => 'bg-light text-dark border', 'icon' => 'fas fa-circle'],
                                };
                                $payPercent = $investment->investment_amount > 0 ? min(100, round(($paidAmount / $investment->investment_amount) * 100)) : 0;
                            @endphp
                            <tr>
                                {{-- 1. Investor / Client --}}
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="inv-avatar">
                                            {{ strtoupper(substr($investment->user->name ?? 'U', 0, 2)) }}
                                        </div>
                                        <div style="min-width: 0;">
                                            <div class="fw-bold text-dark text-truncate mb-0" style="font-size: 0.88rem;">
                                                {{ $investment->user->name ?? 'Deleted User' }}
                                            </div>
                                            <div class="text-muted extra-small text-truncate mt-0.5">
                                                <i class="far fa-envelope me-1 opacity-75"></i>{{ $investment->user->email ?? 'N/A' }}
                                            </div>
                                            <div class="text-muted extra-small mt-0.5">
                                                <i class="far fa-calendar-alt me-1 opacity-75"></i>{{ $investment->created_at ? $investment->created_at->format('d M, Y') : 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- 2. Product Opportunity --}}
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ $postImage }}" alt="{{ $investment->post->title ?? 'Opportunity' }}" class="inv-prod-thumb" />
                                        <div style="min-width: 0;">
                                            <div class="fw-bold text-dark text-truncate mb-1" style="font-size: 0.85rem; max-width: 175px;" title="{{ $investment->post->title ?? 'N/A' }}">
                                                {{ $investment->post->title ?? 'N/A' }}
                                            </div>
                                            <div class="text-muted extra-small mb-1">
                                                Target: <span class="fw-semibold text-dark">&#2547;{{ number_format($investment->post->target_amount ?? 0) }}</span>
                                            </div>
                                            @if($investment->post && $investment->post->expected_import_days)
                                                <div class="d-flex align-items-center gap-1 flex-wrap mt-1">
                                                    <span class="badge bg-light text-muted border inv-badge-sm">
                                                        <i class="fas fa-truck-fast me-1 text-primary"></i>{{ $investment->post->expected_import_days }} Days
                                                    </span>
                                                    <span class="badge {{ ($investment->post->type ?? 'Import') === 'Local' ? 'bg-warning-subtle text-dark border border-warning' : (($investment->post->type ?? 'Import') === 'Manufacture' ? 'bg-info-subtle text-dark border border-info' : 'bg-primary-subtle text-primary border border-primary-subtle') }} px-1.5 py-0.5" style="font-size:0.68rem;">
                                                        <i class="fas {{ ($investment->post->type ?? 'Import') === 'Local' ? 'fa-location-dot' : (($investment->post->type ?? 'Import') === 'Manufacture' ? 'fa-industry' : 'fa-ship') }} me-1"></i>{{ $investment->post->type ?? 'Import' }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- 3. Post Specs --}}
                                <td>
                                    <div class="inv-metrics-box">
                                        <div class="inv-metric-row">
                                            <span class="inv-metric-label">Unit Cost:</span>
                                            <span class="inv-metric-val">&#2547;{{ number_format($investment->post->unit_cost ?? 0, 2) }}</span>
                                        </div>
                                        <div class="inv-metric-row">
                                            <span class="inv-metric-label">Total Qty:</span>
                                            <span class="badge bg-light text-dark border inv-badge-sm">{{ number_format($investment->post->total_quantity ?? 0) }} pcs</span>
                                        </div>
                                        <div class="inv-metric-row">
                                            <span class="inv-metric-label">Target:</span>
                                            <span class="inv-metric-val">&#2547;{{ number_format($investment->post->target_amount ?? 0) }}</span>
                                        </div>
                                        <div class="inv-metric-row">
                                            <span class="inv-metric-label">Unit Profit:</span>
                                            <span class="badge bg-success-subtle text-success border border-success-subtle inv-badge-sm">&#2547;{{ number_format($investment->post->profit_per_unit ?? 0, 2) }}</span>
                                        </div>
                                    </div>
                                </td>

                                {{-- 4. Investment Breakdown --}}
                                <td>
                                    <div class="inv-metrics-box">
                                        <div class="inv-metric-row">
                                            <span class="inv-metric-label">Invested:</span>
                                            <span class="inv-metric-val fw-bold text-dark fs-6">&#2547;{{ number_format($investment->investment_amount) }}</span>
                                        </div>
                                        <div class="inv-metric-row">
                                            <span class="inv-metric-label">Qty Share:</span>
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle inv-badge-sm">{{ number_format($investment->calculated_quantity_share) }} pcs</span>
                                        </div>
                                        <div class="inv-metric-row">
                                            <span class="inv-metric-label">Unit Profit:</span>
                                            <span class="inv-metric-val text-primary">&#2547;{{ number_format($investment->per_piece_profit) }}</span>
                                        </div>
                                        <div class="inv-metric-row">
                                            <span class="inv-metric-label">Exp. Profit:</span>
                                            <span class="badge bg-success-subtle text-success border border-success-subtle inv-badge-sm fw-bold">+&#2547;{{ number_format($investment->expected_profit) }}</span>
                                        </div>
                                    </div>
                                </td>

                                {{-- 5. Payment Status --}}
                                <td>
                                    <div class="inv-metrics-box">
                                        <div class="inv-metric-row">
                                            <span class="inv-metric-label">Paid:</span>
                                            <span class="inv-metric-val {{ $paidAmount > 0 ? 'text-success' : 'text-muted' }}">&#2547;{{ number_format($paidAmount, 2) }}</span>
                                        </div>
                                        <div class="inv-metric-row">
                                            <span class="inv-metric-label">Due:</span>
                                            <span class="inv-metric-val {{ $dueAmount > 0 ? 'text-danger' : 'text-success' }}">&#2547;{{ number_format($dueAmount, 2) }}</span>
                                        </div>
                                        <div class="inv-pay-progress">
                                            <div class="inv-pay-progress-bar {{ $paidAmount >= $investment->investment_amount ? 'bg-success' : 'bg-warning' }}" style="width: {{ $payPercent }}%;"></div>
                                        </div>
                                        <div class="mt-1">
                                            @if($paidAmount >= $investment->investment_amount)
                                                <span class="inv-badge-pill bg-success-subtle text-success border border-success-subtle py-0.5 px-2">
                                                    <i class="fas fa-check-circle"></i> Paid
                                                </span>
                                            @elseif($hasPendingPayment)
                                                <span class="inv-badge-pill bg-info-subtle text-info border border-info-subtle py-0.5 px-2">
                                                    <i class="fas fa-clock"></i> Verifying
                                                </span>
                                            @elseif($paidAmount > 0)
                                                <span class="inv-badge-pill bg-warning-subtle text-warning-emphasis border border-warning-subtle py-0.5 px-2">
                                                    <i class="fas fa-adjust"></i> Partial ({{ $payPercent }}%)
                                                </span>
                                            @else
                                                <span class="inv-badge-pill bg-warning-subtle text-warning-emphasis border border-warning-subtle py-0.5 px-2">
                                                    <i class="fas fa-hourglass-start"></i> Pending
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- 6. Status --}}
                                <td>
                                    <span class="inv-badge-pill {{ $badgeConfig['class'] }}">
                                        <i class="{{ $badgeConfig['icon'] }}"></i> {{ $investment->status }}
                                    </span>
                                </td>

                                {{-- 7. Actions --}}
                                <td class="text-end pe-4">
                                    <div class="d-inline-flex align-items-center gap-1 justify-content-end">
                                        @if(($isAdmin ?? false) && $investment->status === 'pending')
                                            <form action="{{ route('investments.approve', $investment->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Approve this investment bid? This will make it active and generate a payment record.');">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success inv-action-btn shadow-sm" title="Approve Investment Bid">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('investments.reject', $investment->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Reject this investment bid?');">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger inv-action-btn" title="Reject Investment Bid">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Pay Now / Partial Pay button for active investments with due amount --}}
                                        @if(in_array($investment->status, ['active', 'approved', 'sold', 'completed']))
                                            @php
                                                 $hasPendingSlip = $investment->payments->where('status', 'pending')->isNotEmpty();
                                            @endphp
                                            @if($hasPendingSlip)
                                                <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1 rounded-2 extra-small fw-semibold" title="A payment slip is awaiting admin verification">
                                                    <i class="fas fa-clock me-1"></i> Verifying Slip
                                                </span>
                                            @elseif($dueAmount > 0)
                                                <button type="button" class="btn btn-sm btn-success rounded-2 px-2.5 py-1 inv-pay-btn shadow-sm d-inline-flex align-items-center gap-1 fw-semibold extra-small"
                                                     data-bs-toggle="modal" data-bs-target="#investPaymentModal"
                                                     data-investment-id="{{ $investment->id }}"
                                                     data-post-title="{{ $investment->post->title ?? 'Opportunity' }}"
                                                     data-total-amount="{{ $investment->investment_amount }}"
                                                     data-paid-amount="{{ $paidAmount }}"
                                                     data-due-amount="{{ $dueAmount }}"
                                                     data-investor="{{ $investment->user->name ?? '' }}"
                                                     title="Pay remaining due (৳{{ number_format($dueAmount) }})">
                                                     <i class="fas fa-credit-card"></i> {{ $paidAmount > 0 ? 'Pay Due (৳' . number_format($dueAmount) . ')' : 'Pay Now' }}
                                                 </button>
                                            @else
                                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-2 extra-small fw-semibold" title="Payment completed">
                                                    <i class="fas fa-check-circle me-1"></i> Paid
                                                </span>
                                            @endif
                                        @endif

                                        @if(!in_array($investment->status, ['active', 'approved', 'sold', 'completed']))
                                            <form action="{{ route('investments.destroy', $investment->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this investment?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger inv-action-btn" title="Delete Investment">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <div class="py-4">
                                        <div class="mb-3">
                                            <i class="fas fa-hand-holding-dollar fa-3x text-muted opacity-25"></i>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-1">No investments found</h6>
                                        <p class="text-muted small mb-0">Try changing your filters or add a new investment to get started.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ── 6. Pagination ───────────────────────────────────────────── -->
        @if($investments->hasPages())
            <div class="mt-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="text-muted small">
                    Showing {{ $investments->firstItem() ?? 0 }} to {{ $investments->lastItem() ?? 0 }} of {{ $investments->total() }} results
                </div>
                <div>
                    {{ $investments->links() }}
                </div>
            </div>
        @endif

    </div>

    <!-- ── 7. Create Investment Modal ──────────────────────────────────── -->
    <div class="modal fade" id="createInvestmentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header inv-modal-header text-white">
                    <div class="d-flex align-items-center gap-2">
                        <div class="p-2 rounded-3 bg-white bg-opacity-20 text-dark">
                            <i class="fas fa-hand-holding-dollar"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold mb-0 text-white">{{ ($isAdmin ?? false) ? 'Add New Investment' : 'Place New Investment' }}</h5>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('investments.admin_store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            {{-- Investor / Client Selection (Role Aware) --}}
                            @if($isAdmin ?? false)
                                <div class="col-12">
                                    <label class="form-label fw-semibold small text-dark">Investor / Client <span class="text-danger">*</span></label>
                                    <select name="user_id" class="form-select" required>
                                        <option value="" disabled selected>Select Investor / Client...</option>
                                        @foreach($users as $u)
                                            <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            @else
                                <div class="col-12">
                                    <label class="form-label fw-semibold small text-dark">Investor Account</label>
                                    <div class="p-2 bg-light rounded-3 border d-flex align-items-center gap-2">
                                        <div class="inv-avatar" style="width: 36px; height: 36px; font-size: 0.85rem;">
                                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                                        </div>
                                        <div>
                                            <strong class="d-block text-dark small fw-bold">{{ auth()->user()->name }}</strong>
                                            <span class="text-muted extra-small">{{ auth()->user()->email }} (Investor)</span>
                                        </div>
                                    </div>
                                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                                </div>
                            @endif

                            {{-- Product Opportunity Select --}}
                            <div class="col-12">
                                <label class="form-label fw-semibold small text-dark">Product Opportunity <span class="text-danger">*</span></label>
                                <select name="investment_post_id" id="create_post_select" class="form-select" required>
                                    <option value="" disabled selected>Select an active product opportunity...</option>
                                    @foreach($posts->where('status', '!=', 'sold_out') as $post)
                                        <option value="{{ $post->id }}"
                                            data-unit_cost="{{ $post->unit_cost }}"
                                            data-profit_per_unit="{{ $post->profit_per_unit }}"
                                            data-target_amount="{{ $post->target_amount }}"
                                            data-min_investment="{{ $post->min_investment_amount }}">
                                            {{ $post->title }} — Cost/pc: ৳{{ number_format($post->unit_cost) }} | Profit/pc: ৳{{ number_format($post->profit_per_unit) }} | Target: ৳{{ number_format($post->target_amount) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Product Opportunity Live Specs Banner --}}
                            <div id="create_post_specs" class="col-12 d-none">
                                <div class="p-3 bg-light rounded-3 border">
                                    <div class="row g-2 extra-small">
                                        <div class="col-6 col-md-3">
                                            <span class="text-muted d-block">Unit Cost</span>
                                            <strong class="text-dark fs-6" id="create_spec_unit_cost">৳0</strong>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <span class="text-muted d-block">Profit / pc</span>
                                            <strong class="text-success fs-6" id="create_spec_profit_per_unit">৳0</strong>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <span class="text-muted d-block">Target Goal</span>
                                            <strong class="text-dark fs-6" id="create_spec_target">৳0</strong>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <span class="text-muted d-block">Min Investment</span>
                                            <strong class="text-primary fs-6" id="create_spec_min_inv">৳0</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Investment Amount --}}
                            <div class="col-md-6 col-12">
                                <label class="form-label fw-semibold small text-dark">Investment Amount (&#2547;) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 fw-bold">&#2547;</span>
                                    <input type="number" name="amount" id="create_amount" class="form-control" placeholder="e.g. 50000" min="1" step="any" required>
                                </div>
                                <div id="create_min_inv_warning" class="text-danger extra-small mt-1 d-none fw-semibold">
                                    <i class="fas fa-exclamation-circle me-1"></i> Minimum investment is ৳<span id="create_min_inv_text">0</span>.
                                </div>
                            </div>

                            {{-- Live Breakdown --}}
                            <div class="col-md-6 col-12">
                                <label class="form-label fw-semibold small text-dark">Calculated Returns</label>
                                <div class="p-2 bg-light rounded-3 border">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="text-muted extra-small font-monospace">QUANTITY SHARE:</span>
                                        <strong id="create_preview_qty" class="text-primary small fw-bold">0 pcs</strong>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="text-muted extra-small font-monospace">EXPECTED PROFIT:</span>
                                        <strong id="create_preview_profit" class="text-success small fw-bold">+৳0</strong>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center border-top pt-1">
                                        <span class="text-dark extra-small font-monospace fw-semibold">TOTAL RETURN:</span>
                                        <strong id="create_preview_total" class="text-dark small fw-bold">৳0</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light py-3 px-4 border-top">
                        <button type="button" class="btn btn-secondary btn-sm rounded-3 px-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm rounded-3 px-4 fw-semibold shadow-sm" id="create_submit_btn">
                            <i class="fas fa-check-circle me-1"></i> {{ ($isAdmin ?? false) ? 'Create Investment' : 'Submit Investment Bid' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ── 8. Edit Investment Modal ────────────────────────────────────── -->
    <div class="modal fade" id="editInvestmentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header inv-modal-header text-white">
                    <div class="d-flex align-items-center gap-2 text-dark">
                        <i class="fas fa-pen-to-square"></i>
                        <h5 class="modal-title fw-bold mb-0">Edit Investment Record</h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editInvestmentForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6 col-12">
                                <label class="form-label fw-semibold small text-dark">Amount (&#2547;) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 fw-bold">&#2547;</span>
                                    <input type="number" name="amount" id="edit_amount" class="form-control" min="1" step="0.01" required>
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <label class="form-label fw-semibold small text-dark">Quantity Share (pcs) <span class="text-danger">*</span></label>
                                <input type="number" name="calculated_quantity_share" id="edit_quantity_share" class="form-control" min="1" required>
                            </div>

                            <div class="col-md-6 col-12">
                                <label class="form-label fw-semibold small text-dark">Profit / Piece (&#2547;) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 fw-bold">&#2547;</span>
                                    <input type="number" name="per_piece_profit" id="edit_per_piece_profit" class="form-control" min="0" step="0.01" required>
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <label class="form-label fw-semibold small text-dark">Expected Profit (&#2547;)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 fw-bold text-success">+&#2547;</span>
                                    <input type="number" name="expected_profit" id="edit_expected_profit" class="form-control bg-light fw-bold text-success" min="0" step="0.01" readonly required>
                                </div>
                                <small class="text-muted extra-small">Auto-calculated: (Quantity Share &times; Profit/pc)</small>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold small text-dark">Status <span class="text-danger">*</span></label>
                                <select name="status" id="edit_status" class="form-select" required>
                                    <option value="pending">⏳ Pending Approval</option>
                                    <option value="active">✅ Active</option>
                                    <option value="completed">🏆 Completed</option>
                                    <option value="cancelled">❌ Cancelled</option>
                                    <option value="refunded">🔄 Refunded</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light py-3 px-4 border-top">
                        <button type="button" class="btn btn-secondary btn-sm rounded-3 px-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm rounded-3 px-4 fw-semibold shadow-sm">Update Investment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ── 9. Pay Now Modal ────────────────────────────────────────────── -->
    <div class="modal fade" id="investPaymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header inv-modal-header bg-pay text-white">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-credit-card fs-5"></i>
                        <h5 class="modal-title fw-bold mb-0">Submit Investment Payment</h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('investments.pay') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="investment_id" id="invPayInvestmentId">
                    <div class="modal-body p-4">
                        <div class="p-3 bg-light rounded-3 border mb-3">
                            <div class="fw-bold text-dark small mb-1" id="invPayTitle">Investment Opportunity</div>
                            <div class="d-flex justify-content-between extra-small text-muted mb-1">
                                <span>Total Amount: <strong class="text-dark" id="invPayTotal">&#2547;0</strong></span>
                                <span>Paid: <strong class="text-success" id="invPayPaid">&#2547;0</strong></span>
                            </div>
                            <div class="d-flex justify-content-between extra-small border-top pt-1 mt-1">
                                <span class="text-danger fw-bold">Remaining Due:</span>
                                <strong class="text-danger fs-6" id="invPayDue">&#2547;0</strong>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6 col-12">
                                <label class="form-label fw-semibold small text-dark">Payment Amount (&#2547;) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 fw-bold">&#2547;</span>
                                    <input type="number" name="paid_amount" id="invPayAmount" class="form-control" step="any" min="1" required placeholder="Enter amount">
                                </div>
                                <div id="invPayMaxWarning" class="text-danger extra-small mt-1 d-none fw-semibold">
                                    <i class="fas fa-exclamation-circle me-1"></i> Cannot exceed due amount of ৳<span id="invPayMaxDueText">0</span>.
                                </div>
                                <small class="text-muted extra-small d-block mt-1">Full or partial amount accepted (max: due amount).</small>
                            </div>
                            <div class="col-md-6 col-12">
                                <label class="form-label fw-semibold small text-dark">Payment Date <span class="text-danger">*</span></label>
                                <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold small text-dark">Transaction ID / Reference</label>
                                <input type="text" name="transaction_id" class="form-control" placeholder="e.g. TXN123456789">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold small text-dark">Payment Slip / Receipt <span class="text-danger">*</span></label>
                                <input type="file" name="slip" class="form-control" accept="image/jpeg,image/png,image/webp,application/pdf" required>
                                <small class="text-muted" style="font-size:0.72rem;">Upload payment receipt (JPG, PNG, WEBP, or PDF — max 5MB).</small>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold small text-dark">Note (Optional)</label>
                                <textarea name="message" class="form-control" rows="2" placeholder="Any remarks or bank transfer notes..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light py-3 px-4 border-top">
                        <button type="button" class="btn btn-secondary btn-sm rounded-3 px-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success btn-sm rounded-3 px-4 fw-semibold shadow-sm" id="invPaySubmitBtn">
                            <i class="fas fa-paper-plane me-1"></i> Submit Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Edit investment modal dynamic sync
            function syncEditProfit() {
                var qty = parseFloat(document.getElementById('edit_quantity_share').value) || 0;
                var ppp = parseFloat(document.getElementById('edit_per_piece_profit').value) || 0;
                document.getElementById('edit_expected_profit').value = (qty * ppp).toFixed(2);
            }

            var editQtyInput = document.getElementById('edit_quantity_share');
            var editPppInput = document.getElementById('edit_per_piece_profit');
            if (editQtyInput) editQtyInput.addEventListener('input', syncEditProfit);
            if (editPppInput) editPppInput.addEventListener('input', syncEditProfit);

            var editButtons = document.querySelectorAll('.edit-investment-btn');
            editButtons.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var id = this.getAttribute('data-id');
                    var form = document.getElementById('editInvestmentForm');
                    form.action = '{{ url("investments") }}/' + id;

                    var qty = this.getAttribute('data-calculated_quantity_share') || '1';
                    var ppp = this.getAttribute('data-per_piece_profit') || '0';
                    var expected = (parseFloat(qty) * parseFloat(ppp)).toFixed(2);

                    document.getElementById('edit_amount').value = this.getAttribute('data-amount') || '';
                    document.getElementById('edit_quantity_share').value = qty;
                    document.getElementById('edit_per_piece_profit').value = parseFloat(ppp).toFixed(2);
                    document.getElementById('edit_expected_profit').value = expected;
                    document.getElementById('edit_status').value = this.getAttribute('data-status') || 'active';
                });
            });

            // Create investment dynamic preview
            function syncCreatePreview() {
                var select = document.getElementById('create_post_select');
                var specsBox = document.getElementById('create_post_specs');
                var amountInput = document.getElementById('create_amount');
                var minInvWarning = document.getElementById('create_min_inv_warning');
                var minInvText = document.getElementById('create_min_inv_text');

                if (!select) return;
                var opt = select.options[select.selectedIndex];
                if (!opt || !opt.dataset.unit_cost) {
                    if (specsBox) specsBox.classList.add('d-none');
                    return;
                }

                var unitCost = parseFloat(opt.dataset.unit_cost) || 1;
                var profitPerUnit = parseFloat(opt.dataset.profit_per_unit) || 0;
                var target = parseFloat(opt.dataset.target_amount) || 0;
                var minInv = parseFloat(opt.dataset.min_investment) || 0;
                var amount = parseFloat(amountInput ? amountInput.value : 0) || 0;

                // Show and update specs box
                if (specsBox) {
                    specsBox.classList.remove('d-none');
                    var specCostEl = document.getElementById('create_spec_unit_cost');
                    var specProfitEl = document.getElementById('create_spec_profit_per_unit');
                    var specTargetEl = document.getElementById('create_spec_target');
                    var specMinEl = document.getElementById('create_spec_min_inv');

                    if (specCostEl) specCostEl.textContent = '৳' + Math.round(unitCost).toLocaleString('en-BD');
                    if (specProfitEl) specProfitEl.textContent = '+৳' + Math.round(profitPerUnit).toLocaleString('en-BD');
                    if (specTargetEl) specTargetEl.textContent = '৳' + Math.round(target).toLocaleString('en-BD');
                    if (specMinEl) specMinEl.textContent = '৳' + Math.round(minInv).toLocaleString('en-BD');
                }

                // Min investment check
                if (minInvText) minInvText.textContent = Math.round(minInv).toLocaleString('en-BD');
                if (amount > 0 && minInv > 0 && amount < minInv) {
                    if (minInvWarning) minInvWarning.classList.remove('d-none');
                    if (amountInput) amountInput.setCustomValidity('Minimum investment is ৳' + Math.round(minInv));
                } else {
                    if (minInvWarning) minInvWarning.classList.add('d-none');
                    if (amountInput) amountInput.setCustomValidity('');
                }

                var qty = Math.max(0, Math.floor(amount / unitCost));
                var profit = qty * profitPerUnit;
                var totalReturn = amount + profit;

                var qtyElem = document.getElementById('create_preview_qty');
                var profitElem = document.getElementById('create_preview_profit');
                var totalElem = document.getElementById('create_preview_total');

                if (qtyElem) qtyElem.textContent = qty.toLocaleString() + ' pcs';
                if (profitElem) profitElem.textContent = '+৳' + Math.round(profit).toLocaleString('en-BD');
                if (totalElem) totalElem.textContent = '৳' + Math.round(totalReturn).toLocaleString('en-BD');
            }

            var createPostSelect = document.getElementById('create_post_select');
            var createAmount = document.getElementById('create_amount');
            if (createPostSelect && createAmount) {
                createPostSelect.addEventListener('change', syncCreatePreview);
                createAmount.addEventListener('input', syncCreatePreview);
            }

            // Pay Now modal trigger & dynamic validation
            var payAmountInput = document.getElementById('invPayAmount');
            var payWarning = document.getElementById('invPayMaxWarning');
            var payMaxDueText = document.getElementById('invPayMaxDueText');
            var currentMaxDue = 0;

            if (payAmountInput) {
                payAmountInput.addEventListener('input', function() {
                    var val = parseFloat(this.value);
                    if (isNaN(val) || val <= 0) {
                        this.setCustomValidity('Please enter a valid amount (minimum ৳1)');
                        if (payWarning) payWarning.classList.add('d-none');
                    } else if (currentMaxDue > 0 && val > currentMaxDue) {
                        if (payWarning) payWarning.classList.remove('d-none');
                        this.setCustomValidity('Payment amount cannot exceed ৳' + Math.round(currentMaxDue));
                    } else {
                        if (payWarning) payWarning.classList.add('d-none');
                        this.setCustomValidity('');
                    }
                });
            }

            var payButtons = document.querySelectorAll('.inv-pay-btn');
            payButtons.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var invId = this.getAttribute('data-investment-id');
                    var total = parseFloat(this.getAttribute('data-total-amount')) || 0;
                    var paid = parseFloat(this.getAttribute('data-paid-amount')) || 0;
                    var due = parseFloat(this.getAttribute('data-due-amount')) || Math.max(0, total - paid);
                    var title = this.getAttribute('data-post-title') || 'Investment Opportunity';

                    currentMaxDue = due;
                    document.getElementById('invPayInvestmentId').value = invId;
                    var payInput = document.getElementById('invPayAmount');
                    if (payInput) {
                        payInput.value = due > 0 ? Math.round(due) : Math.round(total);
                        payInput.max   = due > 0 ? Math.round(due) : Math.round(total);
                        payInput.setCustomValidity('');
                    }
                    if (payMaxDueText) payMaxDueText.textContent = Math.round(due).toLocaleString('en-BD');
                    if (payWarning) payWarning.classList.add('d-none');

                    document.getElementById('invPayTitle').textContent = title;
                    document.getElementById('invPayTotal').textContent = '৳' + Math.round(total).toLocaleString('en-BD');
                    document.getElementById('invPayPaid').textContent  = '৳' + Math.round(paid).toLocaleString('en-BD');
                    document.getElementById('invPayDue').textContent   = '৳' + Math.round(due).toLocaleString('en-BD');
                });
            });
        });
    </script>
    @endpush

</x-backend-layout>
