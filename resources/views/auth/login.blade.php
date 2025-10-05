@extends('layouts.app')

@section('title', 'Login - WhatsApp Business Provider')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
        <div class="card">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <i class="fab fa-whatsapp fa-3x text-success mb-3"></i>
                    <h2 class="card-title">Welcome Back</h2>
                    <p class="text-muted">Sign in to your WhatsApp Business Provider account</p>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required
                                   autocomplete="email"
                                   autofocus
                                   placeholder="Enter your email">
                        </div>
                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   id="password"
                                   name="password"
                                   required
                                   autocomplete="current-password"
                                   placeholder="Enter your password">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">
                            Remember me
                        </label>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            Sign In
                        </button>
                    </div>
                </form>

                <div class="text-center mt-4">
                    <p class="mb-0">Don't have an account?
                        <a href="{{ route('register') }}" class="text-decoration-none">
                            <strong>Sign up here</strong>
                        </a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Demo Credentials -->
        <div class="card mt-3">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="fas fa-info-circle me-2"></i>
                    Demo Credentials
                </h6>
                <p class="card-text small text-muted mb-2">
                    <strong>Email:</strong> admin@whatsapp-provider.com<br>
                    <strong>Password:</strong> password123
                </p>
                <button class="btn btn-sm btn-outline-primary" onclick="fillDemoCredentials()">
                    <i class="fas fa-copy me-1"></i>
                    Fill Demo Credentials
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.input-group-text {
    background-color: #f8f9fa;
    border-right: none;
}

.form-control {
    border-left: none;
}

.form-control:focus {
    border-left: none;
    box-shadow: none;
}

.btn-success {
    background: linear-gradient(135deg, #25D366, #128C7E);
    border: none;
    border-radius: 10px;
    padding: 12px;
    font-weight: 600;
}

.btn-success:hover {
    background: linear-gradient(135deg, #128C7E, #075E54);
    transform: translateY(-1px);
    box-shadow: 0 5px 15px rgba(37, 211, 102, 0.3);
}
</style>

<script>
function togglePassword() {
    const passwordField = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');

    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordField.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}

function fillDemoCredentials() {
    document.getElementById('email').value = 'admin@whatsapp-provider.com';
    document.getElementById('password').value = 'password123';
}
</script>
@endsection
