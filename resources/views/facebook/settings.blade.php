@extends('layouts.app')

@section('title', 'Facebook Settings - WhatsApp Business Provider')
@section('page-title', 'Facebook Integration')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Facebook Connection Status -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fab fa-facebook me-2"></i>
                    Facebook Connection
                </h5>
                @if($isConnected)
                    <span class="badge bg-success">Connected</span>
                @else
                    <span class="badge bg-secondary">Not Connected</span>
                @endif
            </div>
            <div class="card-body">
                @if($isConnected)
                    <!-- Connected State -->
                    <div class="row">
                        <div class="col-md-3">
                            @if($user->facebook_picture)
                                <img src="{{ $user->facebook_picture }}" alt="Facebook Profile" class="img-fluid rounded-circle" style="width: 80px; height: 80px;">
                            @else
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                    <i class="fab fa-facebook text-white fa-2x"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-9">
                            <h6 class="mb-1">{{ $user->facebook_name }}</h6>
                            <p class="text-muted mb-1">{{ $user->facebook_email }}</p>
                            <small class="text-muted">
                                Connected: {{ $user->facebook_connected_at->format('M d, Y H:i') }}
                            </small>
                            @if($user->facebook_token_expires_at)
                                <br>
                                <small class="text-muted">
                                    Token expires: {{ $user->facebook_token_expires_at->format('M d, Y H:i') }}
                                </small>
                            @endif
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-primary" onclick="testConnection()">
                                <i class="fas fa-check-circle me-2"></i>
                                Test Connection
                            </button>
                            <button type="button" class="btn btn-outline-warning" onclick="refreshToken()">
                                <i class="fas fa-sync me-2"></i>
                                Refresh Token
                            </button>
                            <form method="POST" action="{{ route('facebook.disconnect') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to disconnect your Facebook account?')">
                                    <i class="fas fa-unlink me-2"></i>
                                    Disconnect
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Not Connected State -->
                    <div class="text-center py-4">
                        <i class="fab fa-facebook fa-4x text-primary mb-3"></i>
                        <h5>Connect Your Facebook Account</h5>
                        <p class="text-muted mb-4">
                            Connect your Facebook account to manage your pages and post content directly from this platform.
                        </p>
                        <a href="{{ route('facebook.redirect') }}" class="btn btn-primary btn-lg">
                            <i class="fab fa-facebook me-2"></i>
                            Connect Facebook Account
                        </a>
                    </div>
                @endif
            </div>
        </div>

        @if($isConnected && !empty($pages))
        <!-- Page Selection -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-th-large me-2"></i>
                    Select Default Page
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('facebook.update-page') }}">
                    @csrf
                    <div class="row">
                        @foreach($pages as $page)
                        <div class="col-md-6 mb-3">
                            <div class="card {{ $user->facebook_selected_page_id == $page['id'] ? 'border-primary' : '' }}">
                                <div class="card-body">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="selected_page_id"
                                               id="page_{{ $page['id'] }}" value="{{ $page['id'] }}"
                                               {{ $user->facebook_selected_page_id == $page['id'] ? 'checked' : '' }}>
                                        <label class="form-check-label w-100" for="page_{{ $page['id'] }}">
                                            <div class="d-flex align-items-center">
                                                @if(isset($page['picture']['data']['url']))
                                                    <img src="{{ $page['picture']['data']['url'] }}" alt="{{ $page['name'] }}"
                                                         class="rounded me-3" style="width: 40px; height: 40px;">
                                                @else
                                                    <div class="bg-primary rounded d-flex align-items-center justify-content-center me-3"
                                                         style="width: 40px; height: 40px;">
                                                        <i class="fas fa-th-large text-white"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <h6 class="mb-0">{{ $page['name'] }}</h6>
                                                    <small class="text-muted">{{ $page['category'] ?? 'Page' }}</small>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            Update Selected Page
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif

        @if($isConnected && !empty($pages))
        <!-- Page Management -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-cogs me-2"></i>
                    Page Management
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Page</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pages as $page)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if(isset($page['picture']['data']['url']))
                                            <img src="{{ $page['picture']['data']['url'] }}" alt="{{ $page['name'] }}"
                                                 class="rounded me-3" style="width: 32px; height: 32px;">
                                        @else
                                            <div class="bg-primary rounded d-flex align-items-center justify-content-center me-3"
                                                 style="width: 32px; height: 32px;">
                                                <i class="fas fa-th-large text-white"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <h6 class="mb-0">{{ $page['name'] }}</h6>
                                            <small class="text-muted">ID: {{ $page['id'] }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $page['category'] ?? 'N/A' }}</td>
                                <td>
                                    @if($user->facebook_selected_page_id == $page['id'])
                                        <span class="badge bg-success">Default</span>
                                    @else
                                        <span class="badge bg-secondary">Available</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" onclick="viewPageDetails('{{ $page['id'] }}')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-bolt me-2"></i>
                    Quick Actions
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if($isConnected)
                        <button class="btn btn-success" onclick="testConnection()">
                            <i class="fas fa-check-circle me-2"></i>
                            Test Connection
                        </button>
                        <button class="btn btn-warning" onclick="refreshToken()">
                            <i class="fas fa-sync me-2"></i>
                            Refresh Token
                        </button>
                    @else
                        <a href="{{ route('facebook.redirect') }}" class="btn btn-primary">
                            <i class="fab fa-facebook me-2"></i>
                            Connect Facebook
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Information -->
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-info-circle me-2"></i>
                    Information
                </h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h6 class="alert-heading">
                        <i class="fas fa-lightbulb me-2"></i>
                        Facebook Integration
                    </h6>
                    <p class="mb-0">
                        Connect your Facebook account to manage your pages and post content directly from this platform.
                    </p>
                </div>

                <div class="mt-3">
                    <h6>Available Features:</h6>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-success me-2"></i>Manage Facebook pages</li>
                        <li><i class="fas fa-check text-success me-2"></i>Post content to pages</li>
                        <li><i class="fas fa-check text-success me-2"></i>View page insights</li>
                        <li><i class="fas fa-check text-success me-2"></i>Manage page metadata</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Page Details Modal -->
<div class="modal fade" id="pageDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-th-large me-2"></i>
                    Page Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="pageDetailsContent">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Test Facebook connection
function testConnection() {
    fetch('{{ route("facebook.test") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('✅ ' + data.message);
        } else {
            alert('❌ ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('❌ Failed to test connection. Please try again.');
    });
}

// Refresh Facebook token
function refreshToken() {
    if (confirm('This will refresh your Facebook token. Continue?')) {
        fetch('{{ route("facebook.refresh") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('✅ ' + data.message);
                location.reload();
            } else {
                alert('❌ ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ Failed to refresh token. Please try again.');
        });
    }
}

// View page details
function viewPageDetails(pageId) {
    const pages = @json($pages);
    const page = pages.find(p => p.id === pageId);

    if (page) {
        const content = `
            <div class="row">
                <div class="col-md-4">
                    ${page.picture ? `<img src="${page.picture.data.url}" alt="${page.name}" class="img-fluid rounded">` : '<div class="bg-primary rounded d-flex align-items-center justify-content-center" style="height: 150px;"><i class="fas fa-th-large text-white fa-3x"></i></div>'}
                </div>
                <div class="col-md-8">
                    <h5>${page.name}</h5>
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Page ID:</strong></td>
                            <td><code>${page.id}</code></td>
                        </tr>
                        <tr>
                            <td><strong>Category:</strong></td>
                            <td>${page.category || 'N/A'}</td>
                        </tr>
                        <tr>
                            <td><strong>Access Token:</strong></td>
                            <td><code>${page.access_token ? page.access_token.substring(0, 20) + '...' : 'N/A'}</code></td>
                        </tr>
                    </table>
                </div>
            </div>
        `;

        document.getElementById('pageDetailsContent').innerHTML = content;
        new bootstrap.Modal(document.getElementById('pageDetailsModal')).show();
    }
}

// Auto-refresh page every 30 seconds
setInterval(function() {
    console.log('Facebook settings auto-refresh');
}, 30000);
</script>
@endsection
