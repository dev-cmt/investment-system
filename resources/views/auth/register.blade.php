<x-auth-layout>
    <div class="text-center mb-3">
        <h5 class="fw-bold text-white mb-1" style="font-size: 1.15rem;">Join InvestHub</h5>
        <p class="text-secondary small mb-0" style="font-size: 0.78rem;">Create your investor account &amp; start earning weekly profit</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Full Name -->
        <div class="mb-2">
            <label for="name" class="form-label-custom">Full Name</label>
            <div class="input-group">
                <span class="input-group-text input-group-text-custom"><i class="fas fa-user"></i></span>
                <input id="name" class="form-control form-control-custom" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="John Doe" />
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-1 text-danger small" />
        </div>

        <!-- Email Address -->
        <div class="mb-2">
            <label for="email" class="form-label-custom">Email Address</label>
            <div class="input-group">
                <span class="input-group-text input-group-text-custom"><i class="fas fa-envelope"></i></span>
                <input id="email" class="form-control form-control-custom" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="name@example.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-danger small" />
        </div>

        <!-- Password -->
        <div class="mb-2">
            <label for="password" class="form-label-custom">Password</label>
            <div class="input-group">
                <span class="input-group-text input-group-text-custom"><i class="fas fa-lock"></i></span>
                <input id="password" class="form-control form-control-custom" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
                <button type="button" class="btn btn-outline-secondary border-start-0 border-secondary text-secondary" style="border-top-right-radius:10px; border-bottom-right-radius:10px; background:#0f172a; padding: 0 12px;" onclick="togglePasswordVisibility('password', 'toggleIcon1')">
                    <i class="fas fa-eye" id="toggleIcon1" style="font-size: 0.82rem;"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-danger small" />
        </div>

        <!-- Confirm Password -->
        <div class="mb-3">
            <label for="password_confirmation" class="form-label-custom">Confirm Password</label>
            <div class="input-group">
                <span class="input-group-text input-group-text-custom"><i class="fas fa-shield-halved"></i></span>
                <input id="password_confirmation" class="form-control form-control-custom" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
                <button type="button" class="btn btn-outline-secondary border-start-0 border-secondary text-secondary" style="border-top-right-radius:10px; border-bottom-right-radius:10px; background:#0f172a; padding: 0 12px;" onclick="togglePasswordVisibility('password_confirmation', 'toggleIcon2')">
                    <i class="fas fa-eye" id="toggleIcon2" style="font-size: 0.82rem;"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-danger small" />
        </div>

        <button type="submit" class="btn btn-auth-submit mb-2.5">
            <i class="fas fa-user-plus me-1.5"></i> Register Account
        </button>

        <div class="text-center pt-2 border-top border-secondary border-opacity-25">
            <span class="text-secondary small" style="font-size: 0.78rem;">Already have an account? </span>
            <a href="{{ route('login') }}" class="auth-link-custom small" style="font-size: 0.78rem;">Sign In</a>
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
