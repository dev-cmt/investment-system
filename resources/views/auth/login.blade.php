<x-auth-layout>
    <div class="text-center mb-3">
        <h5 class="fw-bold text-white mb-1" style="font-size: 1.15rem;">Welcome Back</h5>
        <p class="text-secondary small mb-0" style="font-size: 0.78rem;">Sign in to track your investments &amp; weekly earnings</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-3 text-emerald-400 small" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-2 mb-sm-3">
            <label for="email" class="form-label-custom">Email Address</label>
            <div class="input-group">
                <span class="input-group-text input-group-text-custom"><i class="fas fa-envelope"></i></span>
                <input id="email" class="form-control form-control-custom" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="name@example.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-danger small" />
        </div>

        <!-- Password -->
        <div class="mb-2 mb-sm-3">
            <label for="password" class="form-label-custom">Password</label>
            <div class="input-group">
                <span class="input-group-text input-group-text-custom"><i class="fas fa-lock"></i></span>
                <input id="password" class="form-control form-control-custom" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
                <button type="button" class="btn btn-outline-secondary border-start-0 border-secondary text-secondary" style="border-top-right-radius:10px; border-bottom-right-radius:10px; background:#0f172a; padding: 0 12px;" onclick="togglePasswordVisibility('password', 'togglePasswordIcon')">
                    <i class="fas fa-eye" id="togglePasswordIcon" style="font-size: 0.82rem;"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-danger small" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="form-check mb-0">
                <input id="remember_me" type="checkbox" class="form-check-input bg-dark border-secondary" name="remember">
                <label for="remember_me" class="form-check-label text-secondary small" style="font-size: 0.78rem;">Remember me</label>
            </div>
            @if (Route::has('password.request'))
                <a class="auth-link-custom small" href="{{ route('password.request') }}" style="font-size: 0.78rem;">Forgot password?</a>
            @endif
        </div>

        <button type="submit" class="btn btn-auth-submit mb-2">
            <i class="fas fa-sign-in-alt me-1.5"></i> Log In
        </button>

        <div class="text-center pt-2 border-top border-secondary border-opacity-25">
            <span class="text-secondary small" style="font-size: 0.78rem;">Don't have an account? </span>
            <a href="{{ route('register') }}" class="auth-link-custom small" style="font-size: 0.78rem;">Create an Account</a>
        </div>
    </form>

    <script>
        function togglePasswordVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</x-auth-layout>
