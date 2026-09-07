<x-backend-layout>
    <div class="inv-page-container">

        <!-- ── 1. Hero Header ───────────────────────────────────────────── -->
        <div class="inv-hero-header">
            <div class="d-flex align-items-center gap-3">
                <div class="inv-title-badge">
                    <i class="fas fa-shield-halved"></i>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2">
                        <h4 class="fw-bold mb-0 text-dark">Role &amp; Permission Management</h4>
                        <span class="badge bg-light text-dark border rounded-pill px-2.5 py-1 extra-small fw-semibold">
                            {{ $roles->count() }} Roles
                        </span>
                    </div>
                    <p class="text-muted small mb-0 mt-0.5">Manage user roles, assign permission privileges, and control administrative access levels.</p>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('roles.create') }}" class="btn btn-primary btn-sm rounded-3 px-3.5 py-2 fw-semibold d-inline-flex align-items-center gap-2 shadow-sm">
                    <i class="fas fa-plus"></i>
                    <span>Create New Role</span>
                </a>
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

        <!-- ── 2. Role Cards Grid ───────────────────────────────────────── -->
        <div class="row g-4">
            @foreach($roles as $role)
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="inv-table-card p-4 h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1.5 fw-bold text-uppercase">
                                    <i class="fas fa-shield-halved me-1"></i> {{ $role->name }}
                                </span>
                                <span class="text-muted small fw-semibold">
                                    <i class="fas fa-users me-1 text-primary"></i> {{ $role->users_count }} {{ Str::plural('user', $role->users_count) }}
                                </span>
                            </div>

                            <div class="mb-4">
                                <h6 class="extra-small text-uppercase fw-bold text-muted mb-2 font-monospace">Assigned Permissions ({{ $role->permissions->count() }})</h6>
                                <div class="d-flex flex-wrap gap-1" style="max-height: 120px; overflow-y: auto;">
                                    @forelse($role->permissions as $permission)
                                        <span class="badge bg-light text-dark border px-2 py-1 extra-small fw-normal">
                                            {{ $permission->name }}
                                        </span>
                                    @empty
                                        <span class="text-muted extra-small fst-italic">No specific permissions assigned</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- Card Actions -->
                        <div class="pt-3 border-top d-flex align-items-center justify-content-between">
                            <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-outline-primary rounded-3 px-3 py-1.5 fw-semibold extra-small">
                                <i class="fas fa-pen me-1"></i> Edit Role
                            </a>

                            @if(!in_array($role->name, ['superadmin', 'admin', 'investor']))
                                <form action="{{ route('roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this role?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-3 px-3 py-1.5 fw-semibold extra-small">
                                        <i class="fas fa-trash me-1"></i> Delete
                                    </button>
                                </form>
                            @else
                                <span class="extra-small text-muted fst-italic"><i class="fas fa-lock me-1 text-muted"></i> System Protected</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</x-backend-layout>
