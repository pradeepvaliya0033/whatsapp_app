@extends('layouts.app')

@section('title', 'Welcome - WhatsApp Business Provider')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="text-center mb-5">
            <i class="fab fa-whatsapp fa-5x text-success mb-4"></i>
            <h1 class="display-4 fw-bold text-primary">WhatsApp Business Provider</h1>
            <p class="lead text-muted">Complete WhatsApp Business API solution for managing messages, templates, and entities</p>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-paper-plane fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Send Messages</h5>
                        <p class="card-text">Send template and text messages to multiple recipients with ease.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-file-alt fa-3x text-success mb-3"></i>
                        <h5 class="card-title">Manage Templates</h5>
                        <p class="card-text">Create, edit, and manage WhatsApp message templates efficiently.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-chart-line fa-3x text-info mb-3"></i>
                        <h5 class="card-title">Track Analytics</h5>
                        <p class="card-text">Monitor message delivery, success rates, and performance metrics.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center">
            <a href="{{ route('login') }}" class="btn btn-success btn-lg me-3">
                <i class="fas fa-sign-in-alt me-2"></i>
                Sign In
            </a>
            <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg">
                <i class="fas fa-user-plus me-2"></i>
                Create Account
            </a>
        </div>

        <!-- Demo Credentials -->
        <div class="card mt-5">
            <div class="card-body text-center">
                <h5 class="card-title">
                    <i class="fas fa-info-circle me-2"></i>
                    Demo Credentials
                </h5>
                <p class="card-text">
                    <strong>Email:</strong> admin@whatsapp-provider.com<br>
                    <strong>Password:</strong> password123
                </p>
                <button class="btn btn-outline-primary" onclick="fillDemoCredentials()">
                    <i class="fas fa-copy me-1"></i>
                    Use Demo Credentials
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function fillDemoCredentials() {
    window.location.href = "{{ route('login') }}";
}
</script>
@endsection
