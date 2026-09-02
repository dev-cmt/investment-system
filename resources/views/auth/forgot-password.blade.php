<x-auth-layout>
    <div class="text-center mb-4">
        <h4 class="fw-bold text-white mb-1">Reset Password</h4>
        <p class="text-secondary small mb-0">Forgot your password? No problem. Enter your email and we'll send a password reset link.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4 text-emerald-400 small" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-4">
            <label for="email" class="form-label-custom">Email Address</label>
            <div class="input-group">
                <span class="input-group-text input-group-text-custom"><i class="fas fa-envelope"></i></span>
                <input id="email" class="form-control form-control-custom" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="your@email.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger small" />
        </div>

        <button type="submit" class="btn btn-auth-submit mb-3">
            <i class="fas fa-paper-plane me-2"></i> Email Password Reset Link
        </button>

        <div class="text-center pt-2 border-top border-secondary border-opacity-25">
            <a href="{{ route('login') }}" class="auth-link-custom small"><i class="fas fa-arrow-left me-1"></i> Back to Log In</a>
        </div>
    </form>
</x-auth-layout>
