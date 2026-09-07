<x-backend-layout>
    <div class="inv-page-container">

        <!-- ── 1. Hero Header ───────────────────────────────────────────── -->
        <div class="inv-hero-header">
            <div class="d-flex align-items-center gap-3">
                <div class="inv-title-badge">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2">
                        <h4 class="fw-bold mb-0 text-dark">Clients &amp; Members Directory</h4>
                        <span class="badge bg-light text-dark border rounded-pill px-2.5 py-1 extra-small fw-semibold">
                            {{ $users->total() }} Total
                        </span>
                    </div>
                    <p class="text-muted small mb-0 mt-0.5">Manage registered investor profiles, credentials, account details, and system roles.</p>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-primary btn-sm rounded-3 px-3.5 py-2 fw-semibold d-inline-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#createUserModal">
                    <i class="fas fa-user-plus"></i>
                    <span>Add New Client</span>
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
                <i class="fas fa-exclamation-triangle fs-5 text-danger"></i>
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- ── 2. Top Summary / KPI Cards ──────────────────────────────── -->
        @php
            $investorCount = $users->filter(fn($u) => $u->roles->contains('name', 'investor') || $u->roles->isEmpty())->count();
            $adminCount = $users->filter(fn($u) => $u->roles->contains('name', 'admin') || $u->roles->contains('name', 'superadmin'))->count();
            $managerCount = $users->filter(fn($u) => $u->roles->contains('name', 'manager'))->count();
        @endphp
        <div class="inv-stats-grid">
            <div class="inv-stat-card stat-teal">
                <div>
                    <div class="inv-stat-label">Total Accounts</div>
                    <div class="inv-stat-val text-teal-800">{{ $users->total() }}</div>
                    <div class="inv-stat-sub">Registered client profiles</div>
                </div>
                <div class="inv-stat-icon text-teal-600" style="background: #ccfbf1; color: #0d9488;">
                    <i class="fas fa-user-group"></i>
                </div>
            </div>

            <div class="inv-stat-card stat-emerald">
                <div>
                    <div class="inv-stat-label">Investors</div>
                    <div class="inv-stat-val text-success">{{ $investorCount }}</div>
                    <div class="inv-stat-sub">Active bidding participants</div>
                </div>
                <div class="inv-stat-icon text-success" style="background: #ecfdf5; color: #10b981;">
                    <i class="fas fa-hand-holding-dollar"></i>
                </div>
            </div>

            <div class="inv-stat-card stat-blue">
                <div>
                    <div class="inv-stat-label">Administrators</div>
                    <div class="inv-stat-val text-primary">{{ $adminCount }}</div>
                    <div class="inv-stat-sub">Super &amp; System Admins</div>
                </div>
                <div class="inv-stat-icon text-primary" style="background: #eff6ff; color: #3b82f6;">
                    <i class="fas fa-user-shield"></i>
                </div>
            </div>

            <div class="inv-stat-card stat-amber">
                <div>
                    <div class="inv-stat-label">Managers &amp; Staff</div>
                    <div class="inv-stat-val text-warning">{{ $managerCount }}</div>
                    <div class="inv-stat-sub">Operations &amp; support</div>
                </div>
                <div class="inv-stat-icon text-warning" style="background: #fffbeb; color: #f59e0b;">
                    <i class="fas fa-user-tie"></i>
                </div>
            </div>
        </div>

        <!-- ── 3. Filters & Search Bar ──────────────────────────────────── -->
        <div class="inv-filter-card">
            <form method="GET" action="{{ route('users.index') }}" class="row g-2 align-items-center">
                <div class="col-lg-9 col-md-8 col-12">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted ps-3"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-1" placeholder="Search client name, email address, or phone number..." value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-lg-3 col-md-4 col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-dark btn-sm rounded-3 w-100 fw-semibold py-2 d-inline-flex align-items-center justify-content-center gap-1">
                        <i class="fas fa-filter"></i>
                        <span>Filter</span>
                    </button>
                    @if(request()->filled('search'))
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm rounded-3 py-2 px-2.5" title="Clear Filters">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- ── 4. Mobile View (d-md-none) ───────────────────────────────── -->
        <div class="d-md-none">
            @forelse($users as $userItem)
                @php
                    $roleName = $userItem->roles->first()?->name ?? 'investor';
                    $roleBadge = match($roleName) {
                        'superadmin', 'admin' => 'bg-danger-subtle text-danger border border-danger-subtle',
                        'manager'             => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle',
                        default               => 'bg-primary-subtle text-primary border border-primary-subtle',
                    };
                @endphp
                <div class="inv-mobile-card">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center gap-2">
                            @if($userItem->avatar)
                                <img src="{{ asset($userItem->avatar) }}" alt="{{ $userItem->name }}" class="rounded-circle shadow-sm border" style="width: 40px; height: 40px; object-fit: cover;" />
                            @else
                                <div class="inv-avatar">
                                    {{ strtoupper(substr($userItem->name, 0, 2)) }}
                                </div>
                            @endif
                            <div>
                                <strong class="d-block text-dark small fw-bold">{{ $userItem->name }}</strong>
                                <span class="text-muted extra-small">{{ $userItem->email }}</span>
                            </div>
                        </div>
                        <span class="inv-badge-pill {{ $roleBadge }}">
                            {{ $roleName }}
                        </span>
                    </div>

                    <div class="inv-mobile-card-grid">
                        <div>
                            <span class="text-muted d-block extra-small">Phone Number</span>
                            <strong class="text-dark">{{ $userItem->phone ?: 'N/A' }}</strong>
                        </div>
                        <div>
                            <span class="text-muted d-block extra-small">Joined Date</span>
                            <strong class="text-dark">{{ $userItem->created_at->format('d M Y') }}</strong>
                        </div>
                    </div>

                    @if($userItem->bio)
                        <div class="p-2 bg-light rounded-3 border mb-3 extra-small text-muted">
                            {{ $userItem->bio }}
                        </div>
                    @endif

                    <div class="d-flex gap-2 border-top pt-2">
                        <button type="button" class="btn btn-sm btn-outline-primary w-100 rounded-3 edit-user-btn fw-semibold py-1.5"
                            data-bs-toggle="modal"
                            data-bs-target="#editUserModal"
                            data-id="{{ $userItem->id }}"
                            data-name="{{ $userItem->name }}"
                            data-email="{{ $userItem->email }}"
                            data-phone="{{ $userItem->phone }}"
                            data-bio="{{ $userItem->bio }}"
                            data-role="{{ $roleName }}">
                            <i class="fas fa-pen me-1"></i> Edit
                        </button>
                        @if($userItem->id !== auth()->id())
                            <form action="{{ route('users.destroy', $userItem->id) }}" method="POST" class="w-100" onsubmit="return confirm('Are you sure you want to delete this client account?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger w-100 rounded-3 fw-semibold py-1.5">
                                    <i class="fas fa-trash me-1"></i> Delete
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-5 bg-white rounded-4 shadow-sm text-muted">
                    <i class="fas fa-inbox fa-3x mb-3 text-muted opacity-25"></i>
                    <p class="mb-0 fw-semibold">No client accounts found.</p>
                </div>
            @endforelse
        </div>

        <!-- ── 5. Desktop Table View (d-none d-md-block) ────────────────── -->
        <div class="inv-table-card d-none d-md-block">
            <div class="table-responsive">
                <table class="table inv-table align-middle">
                    <thead>
                        <tr>
                            <th class="ps-4" style="min-width: 220px;">Client Name</th>
                            <th style="min-width: 200px;">Email Address</th>
                            <th style="min-width: 150px;">Phone</th>
                            <th style="min-width: 120px;">Role</th>
                            <th style="min-width: 140px;">Joined Date</th>
                            <th class="text-end pe-4" style="min-width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $userItem)
                            @php
                                $roleName = $userItem->roles->first()?->name ?? 'investor';
                                $roleBadge = match($roleName) {
                                    'superadmin', 'admin' => 'bg-danger-subtle text-danger border border-danger-subtle',
                                    'manager'             => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle',
                                    default               => 'bg-primary-subtle text-primary border border-primary-subtle',
                                };
                            @endphp
                            <tr>
                                {{-- 1. Client Name --}}
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-3">
                                        @if($userItem->avatar)
                                            <img src="{{ asset($userItem->avatar) }}" alt="{{ $userItem->name }}" class="rounded-circle shadow-sm border" style="width: 38px; height: 38px; object-fit: cover;" />
                                        @else
                                            <div class="inv-avatar">
                                                {{ strtoupper(substr($userItem->name, 0, 2)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-bold text-dark mb-0" style="font-size: 0.88rem;">{{ $userItem->name }}</div>
                                            @if($userItem->id === auth()->id())
                                                <span class="badge bg-light text-muted border extra-small">You (Current)</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- 2. Email --}}
                                <td>
                                    <span class="text-dark small">
                                        <i class="far fa-envelope text-muted me-1.5 opacity-75"></i>{{ $userItem->email }}
                                    </span>
                                </td>

                                {{-- 3. Phone --}}
                                <td>
                                    @if($userItem->phone)
                                        <span class="text-dark small">
                                            <i class="fas fa-phone text-muted me-1.5 opacity-75"></i>{{ $userItem->phone }}
                                        </span>
                                    @else
                                        <span class="text-muted extra-small">N/A</span>
                                    @endif
                                </td>

                                {{-- 4. Role --}}
                                <td>
                                    <span class="inv-badge-pill {{ $roleBadge }}">
                                        {{ $roleName }}
                                    </span>
                                </td>

                                {{-- 5. Joined Date --}}
                                <td>
                                    <span class="text-muted small">
                                        <i class="far fa-calendar-alt me-1.5 opacity-75"></i>{{ $userItem->created_at->format('d M, Y') }}
                                    </span>
                                </td>

                                {{-- 6. Actions --}}
                                <td class="text-end pe-4">
                                    <div class="d-inline-flex align-items-center gap-1 justify-content-end">
                                        <button type="button" class="btn btn-sm btn-outline-primary inv-action-btn edit-user-btn"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editUserModal"
                                            data-id="{{ $userItem->id }}"
                                            data-name="{{ $userItem->name }}"
                                            data-email="{{ $userItem->email }}"
                                            data-phone="{{ $userItem->phone }}"
                                            data-bio="{{ $userItem->bio }}"
                                            data-role="{{ $roleName }}"
                                            title="Edit Client">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                        @if($userItem->id !== auth()->id())
                                            <form action="{{ route('users.destroy', $userItem->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this client account?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger inv-action-btn" title="Delete Client">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <div class="py-4">
                                        <div class="mb-3">
                                            <i class="fas fa-users fa-3x text-muted opacity-25"></i>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-1">No client accounts found</h6>
                                        <p class="text-muted small mb-0">Create a new client profile or adjust your search keywords.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ── 6. Pagination ───────────────────────────────────────────── -->
        @if($users->hasPages())
            <div class="mt-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="text-muted small">
                    Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} accounts
                </div>
                <div>
                    {{ $users->links() }}
                </div>
            </div>
        @endif

    </div>

    <!-- ── 7. Create Client Modal ──────────────────────────────────────── -->
    <div class="modal fade" id="createUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header inv-modal-header text-white">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-user-plus fs-5"></i>
                        <h5 class="modal-title fw-bold mb-0">Add New Client Account</h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6 col-12">
                                <label class="form-label fw-semibold small text-dark">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" placeholder="e.g. John Doe" required>
                            </div>

                            <div class="col-md-6 col-12">
                                <label class="form-label fw-semibold small text-dark">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
                            </div>

                            <div class="col-md-6 col-12">
                                <label class="form-label fw-semibold small text-dark">Phone Number</label>
                                <input type="text" name="phone" class="form-control" placeholder="+880 1712345678">
                            </div>

                            <div class="col-md-6 col-12">
                                <label class="form-label fw-semibold small text-dark">Role &amp; Permissions</label>
                                <select name="role" class="form-select">
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}" {{ $role->name == 'investor' ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 col-12">
                                <label class="form-label fw-semibold small text-dark">Password <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control" placeholder="Min 8 characters" required>
                            </div>

                            <div class="col-md-6 col-12">
                                <label class="form-label fw-semibold small text-dark">Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold small text-dark">Profile Avatar</label>
                                <input type="file" name="avatar" class="form-control" accept="image/*">
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold small text-dark">Bio / Remarks (Optional)</label>
                                <textarea name="bio" class="form-control" rows="2" placeholder="Optional notes..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light py-3 px-4 border-top">
                        <button type="button" class="btn btn-secondary btn-sm rounded-3 px-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm rounded-3 px-4 fw-semibold shadow-sm">Save Client</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ── 8. Edit Client Modal ────────────────────────────────────────── -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header inv-modal-header text-white">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-pen-to-square fs-5"></i>
                        <h5 class="modal-title fw-bold mb-0">Edit Client Account</h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editUserForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6 col-12">
                                <label class="form-label fw-semibold small text-dark">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="edit_name" class="form-control" required>
                            </div>

                            <div class="col-md-6 col-12">
                                <label class="form-label fw-semibold small text-dark">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="edit_email" class="form-control" required>
                            </div>

                            <div class="col-md-6 col-12">
                                <label class="form-label fw-semibold small text-dark">Phone Number</label>
                                <input type="text" name="phone" id="edit_phone" class="form-control">
                            </div>

                            <div class="col-md-6 col-12">
                                <label class="form-label fw-semibold small text-dark">Role &amp; Permissions</label>
                                <select name="role" id="edit_role" class="form-select">
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 col-12">
                                <label class="form-label fw-semibold small text-dark">Password (Leave blank to keep current)</label>
                                <input type="password" name="password" class="form-control" placeholder="New password">
                            </div>

                            <div class="col-md-6 col-12">
                                <label class="form-label fw-semibold small text-dark">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm new password">
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold small text-dark">Change Profile Avatar</label>
                                <input type="file" name="avatar" class="form-control" accept="image/*">
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold small text-dark">Bio / Remarks</label>
                                <textarea name="bio" id="edit_bio" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light py-3 px-4 border-top">
                        <button type="button" class="btn btn-secondary btn-sm rounded-3 px-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm rounded-3 px-4 fw-semibold shadow-sm">Update Client</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var editButtons = document.querySelectorAll('.edit-user-btn');
            editButtons.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var id = this.getAttribute('data-id');
                    var form = document.getElementById('editUserForm');
                    form.action = '{{ url("users") }}/' + id;

                    document.getElementById('edit_name').value  = this.getAttribute('data-name') || '';
                    document.getElementById('edit_email').value = this.getAttribute('data-email') || '';
                    document.getElementById('edit_phone').value = this.getAttribute('data-phone') || '';
                    document.getElementById('edit_bio').value   = this.getAttribute('data-bio') || '';
                    document.getElementById('edit_role').value  = this.getAttribute('data-role') || 'investor';
                });
            });
        });
    </script>
    @endpush
</x-backend-layout>
