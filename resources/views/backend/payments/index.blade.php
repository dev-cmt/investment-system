<x-backend-layout>
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
            <div>
                <h4 class="fw-bold mb-1"><i class="fas fa-receipt text-primary me-2"></i>Payment Transactions</h4>
                <p class="text-muted small mb-0">Record and manage client payment deposits and payouts.</p>
            </div>
            <button type="button" class="btn btn-primary btn-sm rounded-3 px-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#{{ $isAdmin ? 'createPaymentModal' : 'userPaymentModal' }}">
                <i class="fas fa-plus me-1.5"></i> Add Payment
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
                <form method="GET" action="{{ route('payments.index') }}" class="row g-2">
                    <div class="col-md-5 col-sm-6">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control form-control-sm border-start-0 ps-0" placeholder="Search payment # or transaction ID..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <select name="status" class="form-select form-select-sm">
                            <option value="">All Statuses</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-12">
                        <button type="submit" class="btn btn-secondary btn-sm w-100 fw-semibold"><i class="fas fa-filter me-1"></i> Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MOBILE RESPONSIVE CARD VIEW (d-md-none) -->
        <div class="d-md-none">
            <div class="row g-3">
                @forelse($payments as $payment)
                    @php
                        $badgeClass = match($payment->status) {
                            'approved' => 'bg-success-subtle text-success border-success',
                            'pending' => 'bg-warning-subtle text-warning border-warning',
                            'rejected' => 'bg-danger-subtle text-danger border-danger',
                            'cancelled' => 'bg-secondary-subtle text-secondary border-secondary',
                            default => 'bg-light text-dark',
                        };
                    @endphp
                    <div class="col-12">
                        <div class="admin-data-card">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div>
                                    <strong class="d-block text-dark small fw-bold">{{ $payment->payment_number }}</strong>
                                    <span class="text-muted extra-small">{{ $payment->post->title ?? 'Investment Post' }}</span>
                                </div>
                                <span class="badge {{ $badgeClass }} border rounded-pill px-2.5 py-1 extra-small fw-semibold text-uppercase">
                                    {{ $payment->status }}
                                </span>
                            </div>

                            <div class="data-card-grid">
                                <div><span class="text-muted d-block extra-small">Paid Amount</span><strong class="text-success">&#2547;{{ number_format($payment->paid_amount) }}</strong></div>
                                <div><span class="text-muted d-block extra-small">Date</span><strong>{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') : 'N/A' }}</strong></div>
                                <div class="col-span-2"><span class="text-muted d-block extra-small">Transaction ID</span><span class="badge bg-light text-dark border font-monospace extra-small">{{ $payment->transaction_id ?: 'N/A' }}</span></div>
                            </div>

                            @if($isAdmin)
                            <div class="d-flex gap-2 border-top pt-2">
                                <button type="button" class="btn btn-sm btn-outline-primary w-100 rounded-3 edit-payment-btn fw-semibold"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editPaymentModal"
                                    data-id="{{ $payment->id }}"
                                    data-paid_amount="{{ $payment->paid_amount }}"
                                    data-payment_date="{{ $payment->payment_date }}"
                                    data-transaction_id="{{ $payment->transaction_id }}"
                                    data-message="{{ $payment->message }}"
                                    data-status="{{ $payment->status }}"
                                    data-slip="{{ $payment->slip }}">
                                    <i class="fas fa-pen me-1"></i> Edit
                                </button>
                                <form action="{{ route('payments.destroy', $payment->id) }}" method="POST" class="w-100" onsubmit="return confirm('Are you sure you want to delete this payment record?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger w-100 rounded-3 fw-semibold">
                                        <i class="fas fa-trash me-1"></i> Delete
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-4 bg-white rounded-4 shadow-sm text-muted">No payment records found.</div>
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
                                <th class="ps-4">Payment #</th>
                                <th>Investment Post</th>
                                <th>Paid Amount</th>
                                <th>Date</th>
                                <th>Transaction ID</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $payment)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark mb-0">{{ $payment->payment_number }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $payment->post->title ?? 'Investment Post' }}</div>
                                        @if($isAdmin)<small class="text-muted">{{ $payment->member->name ?? 'Deleted User' }}</small>@endif
                                    </td>
                                    <td class="fw-bold text-success">&#2547;{{ number_format($payment->paid_amount) }}</td>
                                    <td class="text-dark small">{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') : 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark border font-monospace px-2 py-1">{{ $payment->transaction_id ?: 'N/A' }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $badgeClass = match($payment->status) {
                                                'approved' => 'bg-success-subtle text-success border-success',
                                                'pending' => 'bg-warning-subtle text-warning border-warning',
                                                'rejected' => 'bg-danger-subtle text-danger border-danger',
                                                'cancelled' => 'bg-secondary-subtle text-secondary border-secondary',
                                                default => 'bg-light text-dark',
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }} border rounded-pill px-2.5 py-1 small fw-semibold text-uppercase">
                                            {{ $payment->status }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        @if(!$isAdmin)
                                            <span class="text-muted small">View only</span>
                                        @else
                                        <button type="button" class="btn btn-sm btn-outline-primary rounded-2 me-1 edit-payment-btn"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editPaymentModal"
                                            data-id="{{ $payment->id }}"
                                            data-paid_amount="{{ $payment->paid_amount }}"
                                            data-payment_date="{{ $payment->payment_date }}"
                                            data-transaction_id="{{ $payment->transaction_id }}"
                                            data-message="{{ $payment->message }}"
                                            data-status="{{ $payment->status }}"
                                            data-slip="{{ $payment->slip }}"
                                            title="Edit Payment">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                        <form action="{{ route('payments.destroy', $payment->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this payment record?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-2" title="Delete Payment">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">No payment records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if($payments->hasPages())
            <div class="mt-4">
                {{ $payments->links() }}
            </div>
        @endif
    </div>

    @if($isAdmin)
    <!-- CREATE PAYMENT MODAL -->
    <div class="modal fade" id="createPaymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header bg-primary text-white py-3">
                    <h5 class="modal-title fw-bold fs-6"><i class="fas fa-plus-circle me-1"></i> Add Payment Transaction</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('payments.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3">

                            {{-- Step 1: Select Investment Post --}}
                            <div class="col-12">
                                <label class="form-label fw-semibold small">Investment Post <span class="text-danger">*</span></label>
                                <select id="create_post_filter" name="investment_post_id" class="form-select form-select-sm">
                                    <option value="">— Select Post (Filter Users) —</option>
                                    @foreach($posts as $p)
                                        <option value="{{ $p->id }}">{{ $p->title }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted extra-small">Choose a post to filter investors who bid on it.</small>
                            </div>

                            {{-- Hidden investment_id set by JS --}}
                            <input type="hidden" name="investment_id" id="create_investment_id">

                            {{-- Step 2: Select User filtered by post --}}
                            <div class="col-12">
                                <label class="form-label fw-semibold small">Client / Member <span class="text-danger">*</span></label>
                                <select name="member_id" id="create_member_select" class="form-select form-select-sm" required>
                                    <option value="" disabled selected>Select post first to load investors...</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Paid Amount (&#2547;) <span class="text-danger">*</span></label>
                                <input type="number" name="paid_amount" id="create_paid_amount" class="form-control form-control-sm" placeholder="e.g. 50000" min="1" step="0.01" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Payment Date <span class="text-danger">*</span></label>
                                <input type="date" name="payment_date" class="form-control form-control-sm" value="{{ date('Y-m-d') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Transaction ID</label>
                                <input type="text" name="transaction_id" class="form-control form-control-sm" placeholder="e.g. TXN98765432">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select form-select-sm" required>
                                    <option value="approved" selected>Approved</option>
                                    <option value="pending">Pending</option>
                                    <option value="rejected">Rejected</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold small">Payment Slip / Receipt</label>
                                <input type="file" name="slip" class="form-control form-control-sm" accept="image/jpeg,image/png,image/webp,application/pdf">
                                <small class="text-muted extra-small">JPG, PNG, WEBP or PDF — max 5MB.</small>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold small">Note / Message</label>
                                <textarea name="message" class="form-control form-control-sm" rows="2" placeholder="Optional payment note..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light py-2 px-4 border-top">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm px-4 fw-semibold">Save Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @else
    <!-- INVESTOR PAYMENT MODAL -->
    <div class="modal fade" id="userPaymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header bg-success text-white py-3">
                    <h5 class="modal-title fw-bold fs-6"><i class="fas fa-credit-card me-2"></i>Add Payment Transaction</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('investments.pay') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="investment_id" id="payment_investment_id">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Investment Post <span class="text-danger">*</span></label>
                            <select id="payment_investment_select" class="form-select form-select-sm" required>
                                <option value="">Select your investment post</option>
                                @foreach($userInvestments as $investment)
                                    <option value="{{ $investment->id }}" data-amount="{{ $investment->amount }}">
                                        {{ $investment->post->title ?? 'Investment #' . $investment->id }} - &#2547;{{ number_format($investment->amount, 2) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Paid Amount (&#2547;) <span class="text-danger">*</span></label>
                                <input type="number" name="paid_amount" id="payment_paid_amount" class="form-control form-control-sm" min="1" step="0.01" required>
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
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold small">Note (Optional)</label>
                                <textarea name="message" class="form-control form-control-sm" rows="2"></textarea>
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
    @endif

    <!-- EDIT PAYMENT MODAL -->
    @if($isAdmin)<div class="modal fade" id="editPaymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header bg-primary text-white py-3">
                    <h5 class="modal-title fw-bold fs-6"><i class="fas fa-pen-to-square me-1"></i> Edit Payment Transaction</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editPaymentForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Paid Amount (&#2547;) <span class="text-danger">*</span></label>
                                <input type="number" name="paid_amount" id="edit_paid_amount" class="form-control form-control-sm" min="1" step="0.01" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Payment Date <span class="text-danger">*</span></label>
                                <input type="date" name="payment_date" id="edit_payment_date" class="form-control form-control-sm" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Transaction ID</label>
                                <input type="text" name="transaction_id" id="edit_transaction_id" class="form-control form-control-sm">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Status <span class="text-danger">*</span></label>
                                <select name="status" id="edit_status" class="form-select form-select-sm" required>
                                    <option value="approved">Approved</option>
                                    <option value="pending">Pending</option>
                                    <option value="rejected">Rejected</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold small">Replace Payment Slip</label>
                                <input type="file" name="slip" class="form-control form-control-sm" accept="image/jpeg,image/png,image/webp,application/pdf">
                                <div id="edit_current_slip" class="mt-2 d-none">
                                    <small class="text-muted d-block mb-1">Current Slip:</small>
                                    <a id="edit_slip_link" href="#" target="_blank" class="btn btn-sm btn-outline-secondary extra-small">
                                        <i class="fas fa-file me-1"></i> View Current Slip
                                    </a>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold small">Note / Message</label>
                                <textarea name="message" id="edit_message" class="form-control form-control-sm" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light py-2 px-4 border-top">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm px-4 fw-semibold">Update Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>@endif

    @push('scripts')
    <script>
        // --- Post → Investors mapping injected from PHP ---
        const postsWithInvestors = @json($postsWithInvestors);

        document.addEventListener('DOMContentLoaded', function() {

            // CREATE: Post filter → populate Member dropdown
            var postFilter   = document.getElementById('create_post_filter');
            var memberSelect = document.getElementById('create_member_select');
            var paidAmountEl = document.getElementById('create_paid_amount');
            var invIdEl      = document.getElementById('create_investment_id');

            if (postFilter) postFilter.addEventListener('change', function() {
                var postId    = this.value;
                var investors = postsWithInvestors[postId] || [];
                memberSelect.innerHTML = '';
                invIdEl.value = '';
                if (investors.length === 0) {
                    memberSelect.innerHTML = '<option value="" disabled selected>No investors found for this post</option>';
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
                        opt.textContent  = inv.user_name + ' (' + inv.user_email + ') — ৳' + parseFloat(inv.amount).toLocaleString();
                        opt.dataset.amount       = inv.amount;
                        opt.dataset.investmentId = inv.investment_id;
                        memberSelect.appendChild(opt);
                    });
                }
            });

            // Auto-fill amount + investment_id when member selected
            if (memberSelect) memberSelect.addEventListener('change', function() {
                var selected = this.options[this.selectedIndex];
                if (selected && selected.dataset.amount) {
                    paidAmountEl.value = parseFloat(selected.dataset.amount).toFixed(2);
                    invIdEl.value      = selected.dataset.investmentId || '';
                }
            });

            var investmentSelect = document.getElementById('payment_investment_select');
            if (investmentSelect) investmentSelect.addEventListener('change', function() {
                document.getElementById('payment_investment_id').value = this.value;
                var selected = this.options[this.selectedIndex];
                document.getElementById('payment_paid_amount').value = selected.dataset.amount || '';
            });

            // EDIT: populate fields from data attributes
            var editButtons = document.querySelectorAll('.edit-payment-btn');
            editButtons.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var id   = this.getAttribute('data-id');
                    var form = document.getElementById('editPaymentForm');
                    form.action = '{{ url("payments") }}/' + id;

                    document.getElementById('edit_paid_amount').value    = this.getAttribute('data-paid_amount') || '';
                    document.getElementById('edit_payment_date').value   = this.getAttribute('data-payment_date') ? this.getAttribute('data-payment_date').substring(0, 10) : '';
                    document.getElementById('edit_transaction_id').value = this.getAttribute('data-transaction_id') || '';
                    document.getElementById('edit_message').value        = this.getAttribute('data-message') || '';
                    document.getElementById('edit_status').value         = this.getAttribute('data-status') || 'approved';

                   var slip = this.getAttribute('data-slip') || '';
                    var slipWrap = document.getElementById('edit_current_slip');
                    var slipLink = document.getElementById('edit_slip_link');

                    if (slip) {
                        var baseUrl = "{{ url('/') }}";
                        slipLink.href = baseUrl + '/' + slip;
                        slipWrap.classList.remove('d-none');
                    } else {
                        slipWrap.classList.add('d-none');
                    }
                });
            });
        });
    </script>
    @endpush
</x-backend-layout>

