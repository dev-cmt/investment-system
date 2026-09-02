<x-backend-layout>
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
            <div>
                <h4 class="fw-bold mb-1"><i class="fas fa-users text-primary me-2"></i>Clients &amp; Members</h4>
                <p class="text-muted small mb-0">Manage registered client accounts and investor profiles.</p>
            </div>
            <button type="button" class="btn btn-primary btn-sm rounded-3 px-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#createUserModal">
                <i class="fas fa-user-plus me-1.5"></i> Add New Client
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
                <i class="fas fa-exclamation-triangle me-1.5"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Filter Card -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="card-body py-3">
                <form method="GET" action="{{ route('users.index') }}" class="row g-2">
                    <div class="col-md-9 col-sm-8">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control form-control-sm border-start-0 ps-0" placeholder="Search client name, email or phone..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-4">
                        <button type="submit" class="btn btn-secondary btn-sm w-100 fw-semibold"><i class="fas fa-filter me-1"></i> Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MOBILE RESPONSIVE CARD VIEW (d-md-none) -->
        <div class="d-md-none">
            <div class="row g-3">
                @forelse($users as $userItem)
                    @php
                        $roleName = $userItem->roles->first()?->name ?? 'investor';
                        $roleBadge = match($roleName) {
                            'admin' => 'bg-danger-subtle text-danger border-danger',
                            'manager' => 'bg-warning-subtle text-warning border-warning',
                            default => 'bg-primary-subtle text-primary border-primary',
                        };
                    @endphp
                    <div class="col-12">
                        <div class="admin-data-card">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    @if($userItem->avatar)
                                        <img src="{{ asset($userItem->avatar) }}" alt="{{ $userItem->name }}" class="rounded-circle shadow-sm" style="width: 40px; height: 40px; object-fit: cover;" />
                                    @else
                                        <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 0.85rem;">
                                            {{ strtoupper(substr($userItem->name, 0, 2)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <strong class="d-block text-dark small fw-bold">{{ $userItem->name }}</strong>
                                        <span class="text-muted extra-small">{{ $userItem->email }}</span>
                                    </div>
                                </div>
                                <span class="badge {{ $roleBadge }} border rounded-pill px-2.5 py-1 extra-small fw-semibold text-uppercase">
                                    {{ $roleName }}
                                </span>
                            </div>

                            <div class="data-card-grid">
                                <div><span class="text-muted d-block extra-small">Phone</span><strong>{{ $userItem->phone ?: 'N/A' }}</strong></div>
                                <div><span class="text-muted d-block extra-small">Joined</span><strong>{{ $userItem->created_at->format('d M Y') }}</strong></div>
                            </div>

                            <div class="d-flex gap-2 border-top pt-2">
                                <button type="button" class="btn btn-sm btn-outline-primary w-100 rounded-3 edit-user-btn fw-semibold"
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
                                        <button type="submit" class="btn btn-sm btn-outline-danger w-100 rounded-3 fw-semibold">
                                            <i class="fas fa-trash me-1"></i> Delete
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-4 bg-white rounded-4 shadow-sm text-muted">No client accounts found.</div>
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
                                <th class="ps-4">Client</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th>Joined Date</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $userItem)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-3">
                                            @if($userItem->avatar)
                                                <img src="{{ asset($userItem->avatar) }}" alt="{{ $userItem->name }}" class="rounded-circle shadow-sm" style="width: 38px; height: 38px; object-fit: cover;" />
                                            @else
                                                <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; font-size: 0.85rem;">
                                                    {{ strtoupper(substr($userItem->name, 0, 2)) }}
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-bold text-dark mb-0">{{ $userItem->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-dark small">{{ $userItem->email }}</td>
                                    <td class="text-muted small">{{ $userItem->phone ?: 'N/A' }}</td>
                                    <td>
                                        @php
                                            $roleName = $userItem->roles->first()?->name ?? 'investor';
                                            $roleBadge = match($roleName) {
                                                'admin' => 'bg-danger-subtle text-danger border-danger',
                                                'manager' => 'bg-warning-subtle text-warning border-warning',
                                                default => 'bg-primary-subtle text-primary border-primary',
                                            };
                                        @endphp
                                        <span class="badge {{ $roleBadge }} border rounded-pill px-2.5 py-1 small fw-semibold text-uppercase">
                                            {{ $roleName }}
                                        </span>
                                    </td>
                                    <td class="text-muted small">{{ $userItem->created_at->format('d M Y') }}</td>
                                    <td class="text-end pe-4">
                                        <button type="button" class="btn btn-sm btn-outline-primary rounded-2 me-1 edit-user-btn"
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
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-2" title="Delete Client">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">No client accounts found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if($users->hasPages())
            <div class="mt-4">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <!-- CREATE CLIENT MODAL -->
    <div class="modal fade" id="createUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header bg-primary text-white py-3">
                    <h5 class="modal-title fw-bold fs-6"><i class="fas fa-user-plus me-1.5"></i> Add New Client Account</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control form-control-sm" placeholder="e.g. John Doe" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control form-control-sm" placeholder="name@example.com" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Phone Number</label>
                                <input type="text" name="phone" class="form-control form-control-sm" placeholder="+880 1712345678">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Role</label>
                                <select name="role" class="form-select form-select-sm">
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}" {{ $role->name == 'investor' ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Password <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control form-control-sm" placeholder="Min 8 characters" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" class="form-control form-control-sm" placeholder="Repeat password" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold small">Profile Avatar</label>
                                <input type="file" name="avatar" class="form-control form-control-sm" accept="image/*">
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold small">Bio / Notes</label>
                                <textarea name="bio" class="form-control form-control-sm" rows="2" placeholder="Optional notes..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light py-2.5 px-4 border-top">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm px-4 fw-semibold">Save Client Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- EDIT CLIENT MODAL -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header bg-primary text-white py-3">
                    <h5 class="modal-title fw-bold fs-6"><i class="fas fa-pen-to-square me-1.5"></i> Edit Client Account</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editUserForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="edit_name" class="form-control form-control-sm" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="edit_email" class="form-control form-control-sm" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Phone Number</label>
                                <input type="text" name="phone" id="edit_phone" class="form-control form-control-sm">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Role</label>
                                <select name="role" id="edit_role" class="form-select form-select-sm">
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Password (Leave blank to keep current)</label>
                                <input type="password" name="password" class="form-control form-control-sm" placeholder="New password">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control form-control-sm" placeholder="Confirm new password">
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold small">Change Avatar</label>
                                <input type="file" name="avatar" class="form-control form-control-sm" accept="image/*">
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold small">Bio / Notes</label>
                                <textarea name="bio" id="edit_bio" class="form-control form-control-sm" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light py-2.5 px-4 border-top">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm px-4 fw-semibold">Update Client Account</button>
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
                    form.action = '{{ route("users.index") }}/' + id;

                    document.getElementById('edit_name').value = this.getAttribute('data-name') || '';
                    document.getElementById('edit_email').value = this.getAttribute('data-email') || '';
                    document.getElementById('edit_phone').value = this.getAttribute('data-phone') || '';
                    document.getElementById('edit_bio').value = this.getAttribute('data-bio') || '';
                    document.getElementById('edit_role').value = this.getAttribute('data-role') || 'investor';
                });
            });
        });
    </script>
    @endpush
</x-backend-layout>
