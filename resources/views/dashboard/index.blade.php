@extends('layouts.app')

@section('title', 'Dashboard - WhatsApp Business Provider')
@section('page-title', 'Dashboard')

@section('content')
<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-6 col-md-6 mb-4">
        <div class="card stat-card success h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            WhatsApp Integration
                        </div>
                        <div class="h5 mb-0 font-weight-bold">Active</div>
                    </div>
                    <div class="col-auto">
                        <i class="fab fa-whatsapp fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6 col-md-6 mb-4">
        <div class="card stat-card info h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            Templates Available
                        </div>
                        <div class="h5 mb-0 font-weight-bold">Unlimited</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-file-alt fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Quick Actions -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-bolt me-2"></i>
                    Quick Actions
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <a href="{{ route('whatsapp.send') }}" class="btn btn-success btn-lg w-100">
                            <i class="fab fa-whatsapp me-2"></i>
                            Send Message
                        </a>
                    </div>
                    <div class="col-md-6 mb-3">
                        <a href="{{ route('facebook.settings') }}" class="btn btn-primary btn-lg w-100">
                            <i class="fab fa-facebook me-2"></i>
                            Facebook Settings
                        </a>
                    </div>
                    <div class="col-md-6 mb-3">
                        <a href="{{ route('whatsapp.messages') }}" class="btn btn-info btn-lg w-100">
                            <i class="fas fa-history me-2"></i>
                            Message History
                        </a>
                    </div>
                    <div class="col-md-6 mb-3">
                        <a href="{{ route('templates.create') }}" class="btn btn-warning btn-lg w-100">
                            <i class="fas fa-plus me-2"></i>
                            Create Template
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Information Panel -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-info-circle me-2"></i>
                    System Information
                </h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h6 class="alert-heading">
                        <i class="fas fa-lightbulb me-2"></i>
                        Simplified Mode
                    </h6>
                    <p class="mb-0">
                        This WhatsApp integration works directly with the WhatsApp Business API
                        without complex entity/provider management.
                    </p>
                </div>

                <div class="mt-3">
                    <h6>Available Features:</h6>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-success me-2"></i>Send WhatsApp messages</li>
                        <li><i class="fas fa-check text-success me-2"></i>Create WhatsApp templates</li>
                        <li><i class="fas fa-check text-success me-2"></i>Connect Facebook pages</li>
                        <li><i class="fas fa-check text-success me-2"></i>Manage social media</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Getting Started Guide -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-rocket me-2"></i>
                    Getting Started
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="text-center">
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 60px; height: 60px;">
                                <i class="fab fa-facebook text-white fa-lg"></i>
                            </div>
                            <h6>1. Connect Facebook</h6>
                            <p class="text-muted small">Link your Facebook pages</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="text-center">
                            <div class="bg-success rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 60px; height: 60px;">
                                <i class="fab fa-whatsapp text-white fa-lg"></i>
                            </div>
                            <h6>2. Send Messages</h6>
                            <p class="text-muted small">Send WhatsApp messages</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="text-center">
                            <div class="bg-info rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 60px; height: 60px;">
                                <i class="fas fa-file-alt text-white fa-lg"></i>
                            </div>
                            <h6>3. Create Templates</h6>
                            <p class="text-muted small">Design message templates</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="text-center">
                            <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 60px; height: 60px;">
                                <i class="fas fa-cog text-white fa-lg"></i>
                            </div>
                            <h6>4. Manage</h6>
                            <p class="text-muted small">Update settings and preferences</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Auto-refresh dashboard every 30 seconds
setInterval(function() {
    // You can add AJAX call here to refresh data
    console.log('Dashboard auto-refresh');
}, 30000);
</script>
@endsection
