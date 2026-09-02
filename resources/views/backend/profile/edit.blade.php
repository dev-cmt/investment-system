<x-backend-layout>
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
            <div>
                <h4 class="fw-bold mb-1"><i class="fas fa-user-circle text-primary me-2"></i>My Profile</h4>
                <p class="text-muted small mb-0">Update your personal information, profile photo, and security password.</p>
            </div>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm rounded-3 px-3 fw-semibold">
                <i class="fas fa-arrow-left me-1.5"></i> Back to Dashboard
            </a>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm mb-4" role="alert">
                <i class="fas fa-check-circle me-1.5"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('success_password'))
            <div class="alert alert-info alert-dismissible fade show rounded-3 border-0 shadow-sm mb-4" role="alert">
                <i class="fas fa-lock me-1.5"></i> {{ session('success_password') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3 border-0 shadow-sm mb-4" role="alert">
                <i class="fas fa-exclamation-triangle me-1.5"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            <!-- Left Side / Main Profile Form -->
            <div class="col-lg-8">
                <!-- Profile Information Card -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="card-header bg-white border-bottom py-3 px-4">
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="fas fa-id-card text-primary me-2"></i> Profile Information
                        </h6>
                        <small class="text-muted">Update your avatar, contact info, and bio details.</small>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')

                            <!-- Avatar Row -->
                            <div class="d-flex align-items-center gap-4 mb-4 pb-4 border-bottom">
                                <div class="position-relative">
                                    <img id="avatarPreview"
                                         src="{{ $user->avatar_url }}"
                                         alt="{{ $user->name }}"
                                         class="rounded-circle shadow-sm border border-2 border-white"
                                         style="width: 84px; height: 84px; object-fit: cover;" />
                                    <label for="avatarInput" class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center cursor-pointer shadow-sm"
                                           style="width: 28px; height: 28px; font-size: 0.75rem;" title="Upload new photo">
                                        <i class="fas fa-camera"></i>
                                    </label>
                                    <input type="file" name="avatar" id="avatarInput" accept="image/*" class="d-none" onchange="previewAvatar(this)" />
                                </div>
                                <div>
                                    <h5 class="fw-bold text-dark mb-1">{{ $user->name }}</h5>
                                    <div class="text-muted small mb-1">{{ $user->email }}</div>
                                    <div class="d-flex gap-1 flex-wrap">
                                        @foreach($user->getRoleNames() as $role)
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1 extra-small fw-semibold text-uppercase">
                                                {{ $role }}
                                            </span>
                                        @endforeach
                                    </div>
                                    <small class="text-muted extra-small d-block mt-1">Click the camera icon to upload a photo (JPG, PNG, WEBP max 5MB).</small>
                                    @error('avatar')
                                        <span class="text-danger extra-small d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Input Fields -->
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-semibold small">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="form-control form-control-sm" required placeholder="Full Name">
                                    @error('name')
                                        <span class="text-danger extra-small">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-semibold small">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="form-control form-control-sm" required placeholder="name@example.com">
                                    @error('email')
                                        <span class="text-danger extra-small">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="phone" class="form-label fw-semibold small">Phone Number</label>
                                    <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" class="form-control form-control-sm" placeholder="+880 17XXXXXXXX">
                                    @error('phone')
                                        <span class="text-danger extra-small">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small">Member Since</label>
                                    <input type="text" class="form-control form-control-sm bg-light" value="{{ $user->created_at->format('d F Y') }}" readonly disabled>
                                </div>

                                <div class="col-12">
                                    <label for="bio" class="form-label fw-semibold small">Bio / About</label>
                                    <textarea name="bio" id="bio" rows="3" class="form-control form-control-sm" placeholder="Short biography or notes...">{{ old('bio', $user->bio) }}</textarea>
                                    @error('bio')
                                        <span class="text-danger extra-small">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                                <button type="submit" class="btn btn-primary btn-sm px-4 rounded-3 fw-semibold">
                                    <i class="fas fa-save me-1.5"></i> Save Profile
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Security / Change Password Card -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="card-header bg-white border-bottom py-3 px-4">
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="fas fa-lock text-primary me-2"></i> Security &amp; Password
                        </h6>
                        <small class="text-muted">Ensure your account is using a strong password.</small>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('user-password.update') }}">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="current_password" class="form-label fw-semibold small">Current Password</label>
                                    <div class="input-group input-group-sm">
                                        <input type="password" name="current_password" id="current_password" class="form-control" placeholder="••••••••">
                                        <button type="button" class="btn btn-outline-secondary" onclick="togglePwd('current_password', this)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    @error('current_password', 'updatePassword')
                                        <span class="text-danger extra-small">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="password" class="form-label fw-semibold small">New Password</label>
                                    <div class="input-group input-group-sm">
                                        <input type="password" name="password" id="password" class="form-control" placeholder="Min 8 characters">
                                        <button type="button" class="btn btn-outline-secondary" onclick="togglePwd('password', this)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    @error('password', 'updatePassword')
                                        <span class="text-danger extra-small">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="password_confirmation" class="form-label fw-semibold small">Confirm Password</label>
                                    <div class="input-group input-group-sm">
                                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirm new password">
                                        <button type="button" class="btn btn-outline-secondary" onclick="togglePwd('password_confirmation', this)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    @error('password_confirmation', 'updatePassword')
                                        <span class="text-danger extra-small">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                                <button type="submit" class="btn btn-primary btn-sm px-4 rounded-3 fw-semibold">
                                    <i class="fas fa-key me-1.5"></i> Update Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Side / Account Summary & Danger Zone -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 text-center bg-white">
                    <div class="mb-3">
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="rounded-circle shadow-sm border border-3 border-light" style="width: 90px; height: 90px; object-fit: cover;" />
                    </div>
                    <h5 class="fw-bold text-dark mb-0">{{ $user->name }}</h5>
                    <p class="text-muted small mb-2">{{ $user->email }}</p>
                    <div class="d-flex justify-content-center gap-1 mb-3">
                        @foreach($user->getRoleNames() as $role)
                            <span class="badge bg-success-subtle text-success border border-success rounded-pill px-3 py-1 extra-small fw-semibold text-uppercase">
                                {{ $role }}
                            </span>
                        @endforeach
                    </div>
                    <div class="p-3 bg-light rounded-3 border text-start small">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Account Status:</span>
                            <span class="badge bg-success">Active</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Joined Date:</span>
                            <strong class="text-dark">{{ $user->created_at->format('d M Y') }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Phone Number:</span>
                            <strong class="text-dark">{{ $user->phone ?: 'N/A' }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Danger Zone / Delete Account -->
                <div class="card border-danger border-opacity-25 shadow-sm rounded-4 overflow-hidden bg-white">
                    <div class="card-header bg-danger-subtle text-danger border-bottom border-danger-subtle py-3 px-4">
                        <h6 class="fw-bold mb-0">
                            <i class="fas fa-triangle-exclamation me-1.5"></i> Danger Zone
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <p class="small text-muted mb-3">
                            Permanently delete your account and all associated data. This action cannot be undone.
                        </p>
                        <button type="button" class="btn btn-outline-danger btn-sm w-100 rounded-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                            <i class="fas fa-trash-can me-1.5"></i> Delete My Account
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- DELETE ACCOUNT MODAL -->
    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header bg-danger text-white py-3">
                    <h5 class="modal-title fw-bold fs-6"><i class="fas fa-triangle-exclamation me-1.5"></i> Delete Account Confirmation</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body p-4 text-center">
                        <p class="text-muted small mb-3">
                            Are you sure you want to delete your account? All data will be permanently purged. Please enter your password to confirm:
                        </p>
                        <div class="text-start">
                            <label class="form-label fw-semibold small">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control form-control-sm" placeholder="Enter your current password" required>
                            @error('password', 'userDeletion')
                                <span class="text-danger extra-small">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer bg-light py-2.5 px-4 border-top">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger btn-sm px-4 fw-semibold">Yes, Delete Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function previewAvatar(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('avatarPreview').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function togglePwd(fieldId, btn) {
            const field = document.getElementById(fieldId);
            const icon = btn.querySelector('i');
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>
    @endpush
</x-backend-layout>
