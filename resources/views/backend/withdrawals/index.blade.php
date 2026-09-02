<x-backend-layout>
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
            <div>
                <h4 class="fw-bold mb-1">
                    <i class="fas fa-money-bill-transfer text-primary me-2"></i>
                    {{ $isAdmin ? 'Withdrawal Requests Management' : 'My Withdrawal Requests' }}
                </h4>
                <p class="text-muted small mb-0">
                    {{ $isAdmin ? 'Review and process investor profit/balance payout requests.' : 'Request and track your investment profit payouts.' }}
                </p>
            </div>
            <button type="button" class="btn btn-primary btn-sm rounded-3 px-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#requestWithdrawalModal">
                <i class="fas fa-plus-circle me-1.5"></i> Request Payout / Withdrawal
            </button>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm" role="alert">
                <i class="fas fa-check-circle me-1.5"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3 border-0 shadow-sm" role="alert">
                <i class="fas fa-exclamation-circle me-1.5"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Stats Overview Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 p-3 bg-primary-subtle text-primary"><i class="fas fa-receipt fa-xl"></i></div>
                        <div>
                            <span class="text-muted extra-small d-block text-uppercase fw-semibold">Total Requested</span>
                            <h4 class="fw-bold text-dark mb-0">&#2547;{{ number_format($totalRequested, 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 p-3 bg-success-subtle text-success"><i class="fas fa-circle-check fa-xl"></i></div>
                        <div>
                            <span class="text-muted extra-small d-block text-uppercase fw-semibold">Approved / Paid</span>
                            <h4 class="fw-bold text-success mb-0">&#2547;{{ number_format($totalApproved, 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 p-3 bg-warning-subtle text-warning"><i class="fas fa-clock fa-xl"></i></div>
                        <div>
                            <span class="text-muted extra-small d-block text-uppercase fw-semibold">Pending Requests</span>
                            <h4 class="fw-bold text-warning mb-0">{{ $pendingCount }} Request(s)</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="card-body py-3">
                <form method="GET" action="{{ route('withdrawals.index') }}" class="row g-2">
                    <div class="col-md-5 col-sm-6">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control form-control-sm border-start-0 ps-0" placeholder="Search ID, method, account..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <select name="status" class="form-select form-select-sm">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
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

        <!-- DESKTOP TABLE VIEW -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th class="ps-4">Withdrawal #</th>
                                @if($isAdmin) <th>Investor</th> @endif
                                <th>Method & Account</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($withdrawals as $wd)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark mb-0">{{ $wd->withdrawal_number }}</div>
                                        @if($wd->user_note)
                                            <small class="text-muted extra-small text-truncate d-block" style="max-width:180px;" title="{{ $wd->user_note }}">Note: {{ $wd->user_note }}</small>
                                        @endif
                                    </td>
                                    @if($isAdmin)
                                        <td>
                                            <div class="fw-semibold text-dark">{{ $wd->user->name ?? 'User #'.$wd->user_id }}</div>
                                            <small class="text-muted extra-small">{{ $wd->user->email ?? '' }}</small>
                                        </td>
                                    @endif
                                    <td>
                                        <div class="fw-semibold text-dark"><i class="fas fa-building-columns text-primary me-1"></i> {{ $wd->payment_method }}</div>
                                        <small class="text-muted">Acc: <strong>{{ $wd->account_number }}</strong> @if($wd->account_name) ({{ $wd->account_name }}) @endif</small>
                                    </td>
                                    <td class="fw-bold text-dark">&#2547;{{ number_format($wd->amount, 2) }}</td>
                                    <td class="small text-muted">{{ $wd->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        <span class="badge {{ $wd->status_badge_class }} border rounded-pill px-2.5 py-1 small fw-semibold text-uppercase">
                                            {{ $wd->status }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        @if($isAdmin)
                                            <button type="button" class="btn btn-sm btn-outline-primary rounded-2 update-wd-btn"
                                                data-bs-toggle="modal"
                                                data-bs-target="#adminStatusModal"
                                                data-id="{{ $wd->id }}"
                                                data-number="{{ $wd->withdrawal_number }}"
                                                data-status="{{ $wd->status }}"
                                                data-note="{{ $wd->admin_note }}">
                                                <i class="fas fa-pen me-1"></i> Update
                                            </button>
                                        @else
                                            @if($wd->status == 'pending')
                                                <form action="{{ route('withdrawals.update', $wd->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Cancel this withdrawal request?');">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="cancelled">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-2">
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
                                    <td colspan="{{ $isAdmin ? 7 : 6 }}" class="text-center py-5 text-muted">No withdrawal requests found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if($withdrawals->hasPages())
            <div class="mt-4">
                {{ $withdrawals->links() }}
            </div>
        @endif
    </div>

    <!-- REQUEST WITHDRAWAL MODAL -->
    <div class="modal fade" id="requestWithdrawalModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header bg-primary text-white py-3">
                    <h5 class="modal-title fw-bold fs-6"><i class="fas fa-hand-holding-dollar me-1.5"></i> Request Payout / Withdrawal</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('withdrawals.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            @if($isAdmin)
                                <div class="col-12">
                                    <label class="form-label fw-semibold small">Select Investor <span class="text-danger">*</span></label>
                                    <select name="user_id" class="form-select form-select-sm" required>
                                        @foreach($users as $u)
                                            <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Withdrawal Amount (&#2547;) <span class="text-danger">*</span></label>
                                <input type="number" name="amount" class="form-control form-control-sm" placeholder="e.g. 5000" min="10" step="10" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Payment Method <span class="text-danger">*</span></label>
                                <select name="payment_method" class="form-select form-select-sm" required>
                                    <option value="bKash">bKash (Mobile Banking)</option>
                                    <option value="Nagad">Nagad (Mobile Banking)</option>
                                    <option value="Rocket">Rocket (Mobile Banking)</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Account / Phone Number <span class="text-danger">*</span></label>
                                <input type="text" name="account_number" class="form-control form-control-sm" placeholder="017xxxxxxxx or Bank Acc #" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Account Holder Name</label>
                                <input type="text" name="account_name" class="form-control form-control-sm" placeholder="e.g. John Doe">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Bank Name (If Bank Transfer)</label>
                                <input type="text" name="bank_name" class="form-control form-control-sm" placeholder="e.g. Islami Bank">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Branch Name</label>
                                <input type="text" name="branch_name" class="form-control form-control-sm" placeholder="e.g. Dhanmondi Branch">
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold small">Note / Instructions</label>
                                <textarea name="user_note" class="form-control form-control-sm" rows="2" placeholder="Optional payout instructions..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light py-2.5 px-4 border-top">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm px-4 fw-semibold">Submit Payout Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if($isAdmin)
        <!-- ADMIN STATUS UPDATE MODAL -->
        <div class="modal fade" id="adminStatusModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="modal-header bg-primary text-white py-3">
                        <h5 class="modal-title fw-bold fs-6"><i class="fas fa-sliders me-1.5"></i> Update Withdrawal Status</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="adminStatusForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Withdrawal #</label>
                                <input type="text" id="wd_number_display" class="form-control form-control-sm bg-light" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Status <span class="text-danger">*</span></label>
                                <select name="status" id="wd_status" class="form-select form-select-sm" required>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="completed">Completed (Paid)</option>
                                    <option value="rejected">Rejected</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Admin Note / Transaction Ref</label>
                                <textarea name="admin_note" id="wd_admin_note" class="form-control form-control-sm" rows="3" placeholder="Enter bank reference, bKash Trx ID, or note for user..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer bg-light py-2.5 px-4 border-top">
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary btn-sm px-4 fw-semibold">Save Status</button>
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
                    var id = this.getAttribute('data-id');
                    var number = this.getAttribute('data-number');
                    var status = this.getAttribute('data-status');
                    var note = this.getAttribute('data-note');

                    var form = document.getElementById('adminStatusForm');
                    if (form) {
                        form.action = '{{ route("withdrawals.index") }}/' + id;
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
