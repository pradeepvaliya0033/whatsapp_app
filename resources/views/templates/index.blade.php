@extends('layouts.app')

@section('title', 'Templates - WhatsApp Business Provider')
@section('page-title', 'Template Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="h4 mb-0">WhatsApp Templates</h2>
        <p class="text-muted mb-0">Manage your WhatsApp message templates</p>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTemplateModal">
        <i class="fas fa-plus me-2"></i>
        Create Template
    </button>
</div>

<!-- Templates Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="templatesTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Language</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($templates as $template)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <i class="fas fa-file-alt text-white"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $template->name ?? 'N/A' }}</h6>
                                    <small class="text-muted">{{ $template->id ?? 'N/A' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $template->category ?? 'N/A' }}</span>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $template->language ?? 'N/A' }}</span>
                        </td>
                        <td>
                            @php
                                $statusClass = match($template->status ?? 'UNKNOWN') {
                                    'APPROVED' => 'bg-success',
                                    'PENDING' => 'bg-warning',
                                    'REJECTED' => 'bg-danger',
                                    'DISABLED' => 'bg-secondary',
                                    default => 'bg-secondary'
                                };
                            @endphp
                            <span class="badge {{ $statusClass }}">{{ $template->status ?? 'Unknown' }}</span>
                        </td>
                        <td>
                            <small class="text-muted">
                                {{ isset($template->created_time) ? date('M d, Y', strtotime($template->created_time)) : 'N/A' }}
                            </small>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <button class="btn btn-sm btn-outline-info" onclick="viewTemplate('{{ $template->id ?? '' }}')">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteTemplate('{{ $template->id ?? '' }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No templates found</p>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTemplateModal">
                                <i class="fas fa-plus me-2"></i>
                                Create First Template
                            </button>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Template Modal -->
<div class="modal fade" id="createTemplateModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus me-2"></i>
                    Create New Template
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createTemplateForm" method="POST" action="{{ route('templates.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="name" class="form-label">Template Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name') }}"
                                       placeholder="e.g., welcome_template" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="category" class="form-label">Category *</label>
                                <select class="form-select @error('category') is-invalid @enderror"
                                        id="category" name="category" required>
                                    <option value="">Select Category</option>
                                    <option value="TRANSACTIONAL" {{ old('category') == 'TRANSACTIONAL' ? 'selected' : '' }}>Transactional</option>
                                    <option value="MARKETING" {{ old('category') == 'MARKETING' ? 'selected' : '' }}>Marketing</option>
                                    <option value="UTILITY" {{ old('category') == 'UTILITY' ? 'selected' : '' }}>Utility</option>
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="lang_code" class="form-label">Language Code *</label>
                                <select class="form-select @error('lang_code') is-invalid @enderror"
                                        id="lang_code" name="lang_code" required>
                                    <option value="">Select Language</option>
                                    <option value="en_US" {{ old('lang_code') == 'en_US' ? 'selected' : '' }}>English (US)</option>
                                    <option value="en_GB" {{ old('lang_code') == 'en_GB' ? 'selected' : '' }}>English (UK)</option>
                                    <option value="es_ES" {{ old('lang_code') == 'es_ES' ? 'selected' : '' }}>Spanish</option>
                                    <option value="fr_FR" {{ old('lang_code') == 'fr_FR' ? 'selected' : '' }}>French</option>
                                    <option value="de_DE" {{ old('lang_code') == 'de_DE' ? 'selected' : '' }}>German</option>
                                </select>
                                @error('lang_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Header Section -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fas fa-heading me-2"></i>
                                Header (Optional)
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="header_type" class="form-label">Header Type</label>
                                        <select class="form-select" id="header_type" name="header[type]">
                                            <option value="">No Header</option>
                                            <option value="text" {{ old('header.type') == 'text' ? 'selected' : '' }}>Text</option>
                                            <option value="image" {{ old('header.type') == 'image' ? 'selected' : '' }}>Image</option>
                                            <option value="video" {{ old('header.type') == 'video' ? 'selected' : '' }}>Video</option>
                                            <option value="document" {{ old('header.type') == 'document' ? 'selected' : '' }}>Document</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="header_text" class="form-label">Header Text</label>
                                        <input type="text" class="form-control" id="header_text" name="header[text]"
                                               value="{{ old('header.text') }}" placeholder="Header text">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Body Section -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fas fa-paragraph me-2"></i>
                                Body *
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="body" class="form-label">Message Body *</label>
                                <textarea class="form-control @error('body') is-invalid @enderror"
                                          id="body" name="body" rows="4"
                                          placeholder="Enter your message body. Use {{1}}, {{2}}, etc. for parameters." required>{{ old('body') }}</textarea>
                                <div class="form-text">
                                    <small class="text-muted">Use {{1}}, {{2}}, etc. for dynamic parameters</small>
                                </div>
                                @error('body')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Footer Section -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fas fa-align-center me-2"></i>
                                Footer (Optional)
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="footer" class="form-label">Footer Text</label>
                                <input type="text" class="form-control" id="footer" name="footer"
                                       value="{{ old('footer') }}" placeholder="Footer text">
                            </div>
                        </div>
                    </div>

                    <!-- Buttons Section -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fas fa-mouse-pointer me-2"></i>
                                Buttons (Optional)
                            </h6>
                        </div>
                        <div class="card-body">
                            <div id="buttons-container">
                                <!-- Buttons will be added dynamically -->
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="addButton()">
                                <i class="fas fa-plus me-1"></i>
                                Add Button
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>
                        Create Template
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Template Modal -->
<div class="modal fade" id="viewTemplateModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-eye me-2"></i>
                    Template Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="viewTemplateContent">
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
let buttonCount = 0;

// Add button functionality
function addButton() {
    buttonCount++;
    const container = document.getElementById('buttons-container');
    const buttonDiv = document.createElement('div');
    buttonDiv.className = 'row mb-3';
    buttonDiv.id = `button-${buttonCount}`;

    buttonDiv.innerHTML = `
        <div class="col-md-4">
            <label class="form-label">Button Type</label>
            <select class="form-select" name="buttons[${buttonCount}][type]" required>
                <option value="quick_reply">Quick Reply</option>
                <option value="url">URL</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Button Text</label>
            <input type="text" class="form-control" name="buttons[${buttonCount}][text]" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">URL (if URL button)</label>
            <input type="url" class="form-control" name="buttons[${buttonCount}][url]">
        </div>
        <div class="col-md-1">
            <label class="form-label">&nbsp;</label>
            <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="removeButton(${buttonCount})">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;

    container.appendChild(buttonDiv);
}

// Remove button functionality
function removeButton(id) {
    const buttonDiv = document.getElementById(`button-${id}`);
    if (buttonDiv) {
        buttonDiv.remove();
    }
}

// View Template
function viewTemplate(templateId) {
    if (!templateId) {
        alert('Template ID not found');
        return;
    }

    fetch(`/templates/${templateId}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const template = data.data;
                const content = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Basic Information</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>${template.name || 'N/A'}</td>
                                </tr>
                                <tr>
                                    <td><strong>ID:</strong></td>
                                    <td><code>${template.id || 'N/A'}</code></td>
                                </tr>
                                <tr>
                                    <td><strong>Category:</strong></td>
                                    <td><span class="badge bg-info">${template.category || 'N/A'}</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Language:</strong></td>
                                    <td><span class="badge bg-secondary">${template.language || 'N/A'}</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td><span class="badge bg-success">${template.status || 'N/A'}</span></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Template Content</h6>
                            <div class="border p-3 rounded">
                                <strong>Body:</strong><br>
                                <p>${template.components?.find(c => c.type === 'BODY')?.text || 'N/A'}</p>
                                ${template.components?.find(c => c.type === 'HEADER') ? `
                                    <strong>Header:</strong><br>
                                    <p>${template.components.find(c => c.type === 'HEADER').text || 'N/A'}</p>
                                ` : ''}
                                ${template.components?.find(c => c.type === 'FOOTER') ? `
                                    <strong>Footer:</strong><br>
                                    <p>${template.components.find(c => c.type === 'FOOTER').text || 'N/A'}</p>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                `;
                document.getElementById('viewTemplateContent').innerHTML = content;
                new bootstrap.Modal(document.getElementById('viewTemplateModal')).show();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading template data');
        });
}

// Delete Template
function deleteTemplate(templateId) {
    if (!templateId) {
        alert('Template ID not found');
        return;
    }

    if (confirm('Are you sure you want to delete this template? This action cannot be undone.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/templates/${templateId}`;

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';

        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';

        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}

// Show/hide header text field based on header type
document.getElementById('header_type').addEventListener('change', function() {
    const headerText = document.getElementById('header_text');
    if (this.value === 'text') {
        headerText.parentElement.style.display = 'block';
        headerText.required = true;
    } else {
        headerText.parentElement.style.display = 'none';
        headerText.required = false;
    }
});
</script>
@endsection
