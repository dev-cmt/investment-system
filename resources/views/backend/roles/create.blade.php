<x-backend-layout>
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
            <div>
                <h4 class="fw-bold mb-1"><i class="fas fa-plus-circle text-primary me-2"></i>Create New Role</h4>
                <p class="text-muted small mb-0">Define role name and assign granular module permissions.</p>
            </div>
            <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary btn-sm rounded-3 px-3 fw-semibold">
                <i class="fas fa-arrow-left me-1.5"></i> Back to Roles
            </a>
        </div>

        <form action="{{ route('roles.store') }}" method="POST">
            @csrf

            <!-- Role Name Card -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-8">
                            <label for="name" class="form-label fw-semibold small">Role Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="e.g. manager, admin, investor" required
                                class="form-control form-control-sm @error('name') is-invalid @enderror" />
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 pt-md-4">
                            <div class="form-check form-switch pt-2">
                                <input class="form-check-input cursor-pointer" type="checkbox" id="selectAllMaster">
                                <label class="form-check-label fw-bold text-primary small cursor-pointer" for="selectAllMaster">
                                    Select All Permissions
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Permission Module Cards Grid -->
            <div class="row g-4 mb-4">
                @foreach($permissionGroups as $groupName => $permissions)
                    @php
                        $groupId = Str::slug($groupName);
                        $groupCount = count($permissions);
                    @endphp
                    <div class="col-lg-4 col-md-6">
                        <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                            <!-- Card Header -->
                            <div class="card-header bg-light py-2.5 px-3 border-bottom d-flex align-items-center justify-content-between">
                                <div class="form-check mb-0">
                                    <input type="checkbox" class="form-check-input group-select-all cursor-pointer" id="group-{{ $groupId }}" data-group="{{ $groupId }}">
                                    <label class="form-check-label fw-bold text-dark small cursor-pointer" for="group-{{ $groupId }}">
                                        {{ $groupName }} <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2 py-0.5 extra-small ms-1">{{ $groupCount }}</span>
                                    </label>
                                </div>
                            </div>
                            <!-- Card Body: Permission Checkboxes -->
                            <div class="card-body p-3">
                                <div class="d-flex flex-column gap-2">
                                    @foreach($permissions as $permKey => $permLabel)
                                        @php $valName = is_int($permKey) ? $permLabel : $permKey; @endphp
                                        <div class="form-check">
                                            <input type="checkbox" name="permissions[]" value="{{ $valName }}" id="perm-{{ Str::slug($valName) }}"
                                                class="form-check-input permission-item group-{{ $groupId }} cursor-pointer" />
                                            <label class="form-check-label text-muted small cursor-pointer" for="perm-{{ Str::slug($valName) }}">
                                                {{ is_int($permKey) ? ucwords($permLabel) : $permLabel }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Form Submit Actions -->
            <div class="card border-0 shadow-sm rounded-4 p-3 d-flex flex-row justify-content-end gap-2">
                <a href="{{ route('roles.index') }}" class="btn btn-secondary btn-sm rounded-3 px-4">Cancel</a>
                <button type="submit" class="btn btn-primary btn-sm rounded-3 px-4 fw-semibold">
                    <i class="fas fa-check me-1.5"></i> Save Role
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const masterCheckbox = document.getElementById('selectAllMaster');
            const groupCheckboxes = document.querySelectorAll('.group-select-all');
            const permissionCheckboxes = document.querySelectorAll('.permission-item');

            masterCheckbox.addEventListener('change', function () {
                const isChecked = this.checked;
                permissionCheckboxes.forEach(cb => cb.checked = isChecked);
                groupCheckboxes.forEach(cb => cb.checked = isChecked);
            });

            groupCheckboxes.forEach(groupCb => {
                groupCb.addEventListener('change', function () {
                    const groupId = this.getAttribute('data-group');
                    const groupItems = document.querySelectorAll(`.permission-item.group-${groupId}`);
                    groupItems.forEach(cb => cb.checked = this.checked);
                    updateMasterCheckbox();
                });
            });

            permissionCheckboxes.forEach(itemCb => {
                itemCb.addEventListener('change', function () {
                    const groupClass = Array.from(this.classList).find(c => c.startsWith('group-'));
                    if (groupClass) {
                        const groupId = groupClass.replace('group-', '');
                        const groupHeader = document.querySelector(`.group-select-all[data-group="${groupId}"]`);
                        const groupItems = document.querySelectorAll(`.permission-item.${groupClass}`);
                        const allChecked = Array.from(groupItems).every(cb => cb.checked);
                        if (groupHeader) groupHeader.checked = allChecked;
                    }
                    updateMasterCheckbox();
                });
            });

            function updateMasterCheckbox() {
                const allChecked = Array.from(permissionCheckboxes).every(cb => cb.checked);
                masterCheckbox.checked = allChecked;
            }
        });
    </script>
    @endpush
</x-backend-layout>
