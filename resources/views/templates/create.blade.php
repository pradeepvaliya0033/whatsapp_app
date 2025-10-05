@extends('layouts.app')

@section('title', 'Create Template - WhatsApp Business Provider')
@section('page-title', 'Create New Template')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-plus me-2"></i>
                    Create New Template
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('templates.store') }}">
                    @csrf

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

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('templates.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Back to Templates
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            Create Template
                        </button>
                    </div>
                </form>
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
