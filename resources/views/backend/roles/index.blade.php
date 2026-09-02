<x-backend-layout>
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
            <div>
                <h4 class="fw-bold mb-1"><i class="fas fa-shield-halved text-primary me-2"></i>Role &amp; Permission Management</h4>
                <p class="text-muted small mb-0">Manage user roles, assign permissions, and control access levels across the platform.</p>
            </div>
            <a href="{{ route('roles.create') }}" class="btn btn-primary btn-sm rounded-3 px-3 fw-semibold">
                <i class="fas fa-plus me-1.5"></i> Create New Role
            </a>
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

        <!-- Role Cards Grid -->
        <div class="row g-4">
            @foreach($roles as $role)
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1.5 fw-bold text-uppercase">
                                    <i class="fas fa-shield-halved me-1"></i> {{ $role->name }}
                                </span>
                                <span class="text-muted small fw-semibold">
                                    <i class="fas fa-users me-1 text-muted"></i> {{ $role->users_count }} {{ Str::plural('user', $role->users_count) }}
                                </span>
                            </div>

                            <div class="mb-4">
                                <h6 class="extra-small text-uppercase fw-bold text-muted mb-2">Permissions ({{ $role->permissions->count() }})</h6>
                                <div class="d-flex flex-wrap gap-1" style="max-height: 120px; overflow-y: auto;">
                                    @forelse($role->permissions as $permission)
                                        <span class="badge bg-light text-dark border px-2 py-1 extra-small fw-normal">
                                            {{ $permission->name }}
                                        </span>
                                    @empty
                                        <span class="text-muted extra-small italic">No specific permissions assigned</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- Card Actions -->
                        <div class="pt-3 border-top d-flex align-items-center justify-content-between">
                            <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-outline-primary rounded-3 px-3 fw-semibold">
                                <i class="fas fa-edit me-1"></i> Edit Role
                            </a>

                            @if(!in_array($role->name, ['superadmin', 'admin', 'investor']))
                                <form action="{{ route('roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this role?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-3 px-3 fw-semibold">
                                        <i class="fas fa-trash me-1"></i> Delete
                                    </button>
                                </form>
                            @else
                                <span class="extra-small text-muted italic"><i class="fas fa-lock me-1"></i> Core System Role</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-backend-layout>
