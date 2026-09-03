<x-backend-layout>
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
            <div>
                <h4 class="fw-bold mb-1"><i class="fas fa-hand-holding-dollar text-primary me-2"></i>Investments</h4>
                <p class="text-muted small mb-0">Track and manage client investment bids and manual entries.</p>
            </div>
            <button type="button" class="btn btn-primary btn-sm rounded-3 px-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#createInvestmentModal">
                <i class="fas fa-plus me-1.5"></i> Add New Investment
            </button>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm" role="alert">
                <i class="fas fa-check-circle me-1.5"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Filter Card -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="card-body py-3">
                <form method="GET" action="{{ route('investments.index') }}" class="row g-2">
                    <div class="col-md-4 col-sm-6">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control form-control-sm border-start-0 ps-0" placeholder="Search investor or product..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <select name="status" class="form-select form-select-sm">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending Approval</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <select name="investment_post_id" class="form-select form-select-sm">
                            <option value="">All Product Posts</option>
                            @foreach($posts as $filterPost)
                                <option value="{{ $filterPost->id }}" {{ request('investment_post_id') == $filterPost->id ? 'selected' : '' }}>
                                    {{ $filterPost->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 col-12">
                        <button type="submit" class="btn btn-secondary btn-sm w-100 fw-semibold"><i class="fas fa-filter me-1"></i> Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MOBILE RESPONSIVE CARD VIEW (d-md-none) -->
        <div class="d-md-none">
            <div class="row g-3">
                @forelse($investments as $investment)
                    @php
                        $badgeClass = match($investment->status) {
                            'pending' => 'bg-warning-subtle text-warning border-warning',
                            'active' => 'bg-success-subtle text-success border-success',
                            'completed' => 'bg-primary-subtle text-primary border-primary',
                            'cancelled' => 'bg-danger-subtle text-danger border-danger',
                            'refunded' => 'bg-warning-subtle text-warning border-warning',
                            default => 'bg-light text-dark',
                        };
                    @endphp
                    <div class="col-12">
                        <div class="admin-data-card">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; font-size: 0.85rem;">
                                        {{ strtoupper(substr($investment->user->name ?? 'U', 0, 2)) }}
                                    </div>
                                    <div>
                                        <strong class="d-block text-dark small fw-bold">{{ $investment->user->name ?? 'Deleted User' }}</strong>
                                        <span class="text-muted extra-small">{{ $investment->user->email ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                <span class="badge {{ $badgeClass }} border rounded-pill px-2.5 py-1 extra-small fw-semibold text-uppercase">
                                    {{ $investment->status }}
                                </span>
                            </div>

                            <div class="d-flex gap-2 p-2 bg-light rounded-3 border mb-2">
                                <img src="{{ $investment->post->gallery_image_urls[0] ?? asset('asset/images/earbuds.jpg') }}" alt="" class="rounded-2 object-fit-cover" style="width: 48px; height: 48px;">
                                <div>
                                    <span class="text-muted extra-small d-block">Product Opportunity</span>
                                    <strong class="text-dark small">{{ $investment->post->title ?? 'N/A' }}</strong>
                                </div>
                            </div>

                            <div class="data-card-grid">
                                <div><span class="text-muted d-block extra-small">Invested</span><strong class="text-dark">&#2547;{{ number_format($investment->amount) }}</strong></div>
                                <div><span class="text-muted d-block extra-small">Quantity</span><strong>{{ number_format($investment->calculated_quantity_share) }} pcs</strong></div>
                                <div><span class="text-muted d-block extra-small">Expected Profit</span><strong class="text-success">&#2547;{{ number_format($investment->expected_profit) }}</strong></div>
                                <div><span class="text-muted d-block extra-small">Per Piece</span><strong>&#2547;{{ number_format($investment->post->profit_per_unit ?? 0, 2) }}</strong></div>
                                @php $paidAmount = $investment->payments->where('status', 'approved')->sum('paid_amount'); @endphp
                                <div><span class="text-muted d-block extra-small">Payment</span><strong class="{{ $paidAmount >= $investment->amount ? 'text-success' : 'text-warning' }}">&#2547;{{ number_format($paidAmount, 2) }} / {{ number_format($investment->amount, 2) }}</strong></div>
                                <div><span class="text-muted d-block extra-small">Target</span><span class="text-muted">&#2547;{{ number_format($investment->post->target_amount ?? 0) }}</span></div>
                            </div>

                            <div class="d-flex gap-2 border-top pt-2">
                                @if(($isAdmin ?? false) && $investment->status === 'pending')
                                    <form action="{{ route('investments.approve', $investment->id) }}" method="POST" class="w-100" onsubmit="return confirm('Approve this investment bid? This will make it active and generate a payment record.');">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success w-100 rounded-3 fw-semibold">
                                            <i class="fas fa-check me-1"></i> Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('investments.reject', $investment->id) }}" method="POST" class="w-100" onsubmit="return confirm('Reject this investment bid?');">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger w-100 rounded-3 fw-semibold">
                                            <i class="fas fa-times me-1"></i> Reject
                                        </button>
                                    </form>
                                @endif
                                {{-- Pay Now button for active investments without a payment --}}
                                @if(in_array($investment->status, ['active', 'approved']))
                                    @php $latestPay = $investment->payments->sortByDesc('created_at')->first(); @endphp
                                    @if(!$latestPay || $latestPay->status === 'rejected')
                                        <button type="button" class="btn btn-sm btn-success w-100 rounded-3 fw-semibold inv-pay-btn"
                                            data-bs-toggle="modal" data-bs-target="#investPaymentModal"
                                            data-investment-id="{{ $investment->id }}"
                                            data-post-title="{{ $investment->post->title ?? 'Opportunity' }}"
                                            data-amount="{{ $investment->amount }}"
                                            data-investor="{{ $investment->user->name ?? '' }}">
                                            <i class="fas fa-credit-card me-1"></i> Pay Now
                                        </button>
                                    @elseif($latestPay->status === 'pending')
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1 rounded-2 fw-semibold w-100 text-center">
                                            <i class="fas fa-clock me-1"></i> Payment Pending
                                        </span>
                                    @else
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-2 fw-semibold w-100 text-center">
                                            <i class="fas fa-check-circle me-1"></i> Paid
                                        </span>
                                    @endif
                                @endif
                                @if(in_array($investment->status, ['active', 'completed', 'sold']))
                                    <button type="button" class="btn btn-sm btn-secondary opacity-50 w-100 rounded-3 fw-semibold" disabled title="Approved investment cannot be edited">
                                        <i class="fas fa-lock me-1"></i> Edit
                                    </button>
                                @else
                                    <button type="button" class="btn btn-sm btn-outline-primary w-100 rounded-3 edit-investment-btn fw-semibold"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editInvestmentModal"
                                        data-id="{{ $investment->id }}"
                                        data-amount="{{ $investment->amount }}"
                                        data-calculated_quantity_share="{{ $investment->calculated_quantity_share }}"
                                        data-expected_profit="{{ $investment->expected_profit }}"
                                        data-status="{{ $investment->status }}">
                                        <i class="fas fa-pen me-1"></i> Edit
                                    </button>
                                @endif
                                <form action="{{ route('investments.destroy', $investment->id) }}" method="POST" class="w-100" onsubmit="return confirm('Are you sure you want to delete this investment?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger w-100 rounded-3 fw-semibold">
                                        <i class="fas fa-trash me-1"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-4 bg-white rounded-4 shadow-sm text-muted">No investments found.</div>
                @endforelse
            </div>
        </div>

        <!-- DESKTOP TABLE VIEW (d-none d-md-block) -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden d-none d-md-block">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th class="ps-4">Investor / Client</th>
                                <th>Product Opportunity</th>
                                <th>Invested Amount</th>
                                <th>Quantity Share</th>
                                <th>Expected Profit</th>
                                <th>Per Piece Profit</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($investments as $investment)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; font-size: 0.82rem;">
                                                {{ strtoupper(substr($investment->user->name ?? 'U', 0, 2)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark mb-0">{{ $investment->user->name ?? 'Deleted User' }}</div>
                                                <small class="text-muted">{{ $investment->user->email ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $investment->post->title ?? 'N/A' }}</div>
                                        <small class="text-muted">Target: &#2547;{{ number_format($investment->post->target_amount ?? 0) }}</small>
                                    </td>
                                    <td class="fw-bold text-dark">&#2547;{{ number_format($investment->amount) }}</td>
                                    <td><span class="badge bg-light text-dark border px-2.5 py-1 fw-semibold">{{ number_format($investment->calculated_quantity_share) }} pcs</span></td>
                                    <td class="fw-bold text-success">&#2547;{{ number_format($investment->expected_profit) }}</td>
                                    <td>&#2547;{{ number_format($investment->post->profit_per_unit ?? 0, 2) }}</td>
                                    @php $paidAmount = $investment->payments->where('status', 'approved')->sum('paid_amount'); @endphp
                                    <td>
                                        <span class="fw-semibold {{ $paidAmount >= $investment->amount ? 'text-success' : 'text-warning' }}">&#2547;{{ number_format($paidAmount, 2) }}</span>
                                        <small class="d-block text-muted">of &#2547;{{ number_format($investment->amount, 2) }}</small>
                                        <small class="badge {{ $paidAmount >= $investment->amount ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning-emphasis' }}">{{ $paidAmount >= $investment->amount ? 'Paid' : 'Due' }}</small>
                                    </td>
                                    <td>
                                        @php
                                            $badgeClass = match($investment->status) {
                                                'pending' => 'bg-warning-subtle text-warning border-warning',
                                                'active' => 'bg-success-subtle text-success border-success',
                                                'completed' => 'bg-primary-subtle text-primary border-primary',
                                                'cancelled' => 'bg-danger-subtle text-danger border-danger',
                                                'refunded' => 'bg-warning-subtle text-warning border-warning',
                                                default => 'bg-light text-dark',
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }} border rounded-pill px-2.5 py-1 small fw-semibold text-uppercase">
                                            {{ $investment->status }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        @if(($isAdmin ?? false) && $investment->status === 'pending')
                                            <form action="{{ route('investments.approve', $investment->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Approve this investment bid? This will make it active and generate a payment record.');">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success rounded-2 me-1" title="Approve Bid">
                                                    <i class="fas fa-check me-1"></i> Approve
                                                </button>
                                            </form>
                                            <form action="{{ route('investments.reject', $investment->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Reject this investment bid?');">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-2 me-1" title="Reject Bid">
                                                    <i class="fas fa-times me-1"></i> Reject
                                                </button>
                                            </form>
                                        @endif
                                        {{-- Pay Now button for active investments --}}
                                        @if(in_array($investment->status, ['active', 'approved']))
                                            @php $latestPay = $investment->payments->sortByDesc('created_at')->first(); @endphp
                                            @if(!$latestPay || $latestPay->status === 'rejected')
                                                <button type="button" class="btn btn-sm btn-success rounded-2 me-1 inv-pay-btn"
                                                    data-bs-toggle="modal" data-bs-target="#investPaymentModal"
                                                    data-investment-id="{{ $investment->id }}"
                                                    data-post-title="{{ $investment->post->title ?? 'Opportunity' }}"
                                                    data-amount="{{ $investment->amount }}"
                                                    data-investor="{{ $investment->user->name ?? '' }}"
                                                    title="Submit Payment">
                                                    <i class="fas fa-credit-card me-1"></i> Pay Now
                                                </button>
                                            @elseif($latestPay->status === 'pending')
                                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1 rounded-2 fw-semibold me-1" title="Awaiting payment approval">
                                                    <i class="fas fa-clock me-1"></i> Pending
                                                </span>
                                            @else
                                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-2 fw-semibold me-1" title="Payment approved">
                                                    <i class="fas fa-check-circle me-1"></i> Paid
                                                </span>
                                            @endif
                                        @endif
                                        @if(in_array($investment->status, ['active', 'completed', 'sold']))
                                            <button type="button" class="btn btn-sm btn-secondary opacity-50 rounded-2 me-1" disabled title="Approved investments cannot be edited">
                                                <i class="fas fa-lock"></i>
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-sm btn-outline-primary rounded-2 me-1 edit-investment-btn"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editInvestmentModal"
                                                data-id="{{ $investment->id }}"
                                                data-amount="{{ $investment->amount }}"
                                                data-calculated_quantity_share="{{ $investment->calculated_quantity_share }}"
                                                data-expected_profit="{{ $investment->expected_profit }}"
                                                data-status="{{ $investment->status }}"
                                                title="Edit Investment">
                                                <i class="fas fa-pen"></i>
                                            </button>
                                        @endif
                                        <form action="{{ route('investments.destroy', $investment->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this investment?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-2" title="Delete Investment">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5 text-muted">No investments found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if($investments->hasPages())
            <div class="mt-4">
                {{ $investments->links() }}
            </div>
        @endif
    </div>

    <!-- CREATE INVESTMENT MODAL -->
    <div class="modal fade" id="createInvestmentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header bg-primary text-white py-3">
                    <h5 class="modal-title fw-bold fs-6"><i class="fas fa-plus-circle me-1.5"></i> Add New Investment</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('investments.admin_store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold small">Investor / Client <span class="text-danger">*</span></label>
                                <select name="user_id" class="form-select form-select-sm" required>
                                    <option value="" disabled selected>Select Client...</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold small">Product Opportunity <span class="text-danger">*</span></label>
                                <select name="investment_post_id" class="form-select form-select-sm" required>
                                    <option value="" disabled selected>Select Product Post...</option>
                                    @foreach($posts as $post)
                                        <option value="{{ $post->id }}">{{ $post->title }} (Target: &#2547;{{ number_format($post->target_amount) }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Investment Amount (&#2547;) <span class="text-danger">*</span></label>
                                <input type="number" name="amount" class="form-control form-control-sm" placeholder="e.g. 500000" min="1" step="0.01" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select form-select-sm" required>
                                    <option value="pending">Pending Approval</option>
                                    <option value="active" selected>Active</option>
                                    <option value="completed">Completed</option>
                                    <option value="cancelled">Cancelled</option>
                                    <option value="refunded">Refunded</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light py-2.5 px-4 border-top">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm px-4 fw-semibold">Save Investment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- EDIT INVESTMENT MODAL -->
    <div class="modal fade" id="editInvestmentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header bg-primary text-white py-3">
                    <h5 class="modal-title fw-bold fs-6"><i class="fas fa-pen-to-square me-1.5"></i> Edit Investment</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editInvestmentForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Amount (&#2547;) <span class="text-danger">*</span></label>
                                <input type="number" name="amount" id="edit_amount" class="form-control form-control-sm" min="1" step="0.01" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Quantity Share (pcs) <span class="text-danger">*</span></label>
                                <input type="number" name="calculated_quantity_share" id="edit_quantity_share" class="form-control form-control-sm" min="1" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Expected Profit (&#2547;) <span class="text-danger">*</span></label>
                                <input type="number" name="expected_profit" id="edit_expected_profit" class="form-control form-control-sm" min="0" step="0.01" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Status <span class="text-danger">*</span></label>
                                <select name="status" id="edit_status" class="form-select form-select-sm" required>
                                    <option value="pending">Pending Approval</option>
                                    <option value="active">Active</option>
                                    <option value="completed">Completed</option>
                                    <option value="cancelled">Cancelled</option>
                                    <option value="refunded">Refunded</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light py-2.5 px-4 border-top">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm px-4 fw-semibold">Update Investment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Edit investment modal
            var editButtons = document.querySelectorAll('.edit-investment-btn');
            editButtons.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var id = this.getAttribute('data-id');
                    var form = document.getElementById('editInvestmentForm');
                    form.action = '{{ route("investments.index") }}/' + id;

                    document.getElementById('edit_amount').value = this.getAttribute('data-amount') || '';
                    document.getElementById('edit_quantity_share').value = this.getAttribute('data-calculated_quantity_share') || '';
                    document.getElementById('edit_expected_profit').value = this.getAttribute('data-expected_profit') || '';
                    document.getElementById('edit_status').value = this.getAttribute('data-status') || 'active';
                });
            });

            // Pay Now modal
            var payButtons = document.querySelectorAll('.inv-pay-btn');
            payButtons.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    document.getElementById('invPayInvestmentId').value = this.getAttribute('data-investment-id');
                    document.getElementById('invPayAmount').value = parseFloat(this.getAttribute('data-amount')).toFixed(2);
                    var title = this.getAttribute('data-post-title');
                    var investor = this.getAttribute('data-investor');
                    document.getElementById('invPayInfo').textContent =
                        'Investment: ' + title + (investor ? '  |  Investor: ' + investor : '') +
                        '  |  Amount: ৳' + parseFloat(this.getAttribute('data-amount')).toLocaleString('en-BD', {minimumFractionDigits: 2});
                });
            });
        });
    </script>
    @endpush

    {{-- PAY NOW PAYMENT MODAL --}}
    <div class="modal fade" id="investPaymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header bg-success text-white py-3">
                    <h5 class="modal-title fw-bold fs-6"><i class="fas fa-credit-card me-2"></i>Submit Payment</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('investments.pay') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="investment_id" id="invPayInvestmentId">
                    <div class="modal-body p-4">
                        <div class="alert alert-success border border-success-subtle rounded-3 mb-3 py-2 px-3 small" id="invPaySummary">
                            <i class="fas fa-info-circle me-1 text-success"></i>
                            <span id="invPayInfo"></span>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Paid Amount (&#2547;) <span class="text-danger">*</span></label>
                                <input type="number" name="paid_amount" id="invPayAmount" class="form-control form-control-sm" step="0.01" min="1" required>
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
                                <small class="text-muted" style="font-size:0.72rem;">Upload receipt (JPG, PNG, WEBP, or PDF — max 5MB).</small>
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

</x-backend-layout>
