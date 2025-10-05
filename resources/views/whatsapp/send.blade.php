@extends('layouts.app')

@section('title', 'Send Message - WhatsApp Business Provider')
@section('page-title', 'Send WhatsApp Message')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fab fa-whatsapp me-2"></i>
                    Send WhatsApp Message
                </h5>
            </div>
            <div class="card-body">
                <!-- Message Type Tabs -->
                <ul class="nav nav-tabs mb-4" id="messageTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="template-tab" data-bs-toggle="tab" data-bs-target="#template" type="button" role="tab">
                            <i class="fas fa-file-alt me-2"></i>
                            Template Message
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="text-tab" data-bs-toggle="tab" data-bs-target="#text" type="button" role="tab">
                            <i class="fas fa-comment me-2"></i>
                            Text Message
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="messageTabsContent">
                    <!-- Template Message Tab -->
                    <div class="tab-pane fade show active" id="template" role="tabpanel">
                        <form id="templateMessageForm" method="POST" action="{{ route('whatsapp.send-message') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="template_name" class="form-label">Template Name *</label>
                                        <input type="text" class="form-control @error('template_name') is-invalid @enderror"
                                               id="template_name" name="template_name" value="{{ old('template_name') }}"
                                               placeholder="e.g., welcome_template" required>
                                        @error('template_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
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

                            <div class="mb-3">
                                <label for="recipients" class="form-label">Recipients *</label>
                                <textarea class="form-control @error('to') is-invalid @enderror"
                                          id="recipients" name="to" rows="3"
                                          placeholder="Enter phone numbers (one per line or comma-separated)&#10;e.g., +1234567890, +0987654321" required>{{ old('to') }}</textarea>
                                <div class="form-text">
                                    <small class="text-muted">Format: +1234567890 (include country code)</small>
                                </div>
                                @error('to')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="parameters" class="form-label">Template Parameters</label>
                                <textarea class="form-control" id="parameters" name="parameters" rows="2"
                                          placeholder="Enter parameters separated by commas&#10;e.g., John, 12345">{{ old('parameters') }}</textarea>
                                <div class="form-text">
                                    <small class="text-muted">Parameters will replace {{1}}, {{2}}, etc. in your template</small>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fab fa-whatsapp me-2"></i>
                                    Send Template Message
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Text Message Tab -->
                    <div class="tab-pane fade" id="text" role="tabpanel">
                        <form id="textMessageForm" method="POST" action="{{ route('whatsapp.send-text') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="text_recipient" class="form-label">Recipient *</label>
                                <input type="text" class="form-control @error('to') is-invalid @enderror"
                                       id="text_recipient" name="to" value="{{ old('to') }}"
                                       placeholder="+1234567890" required>
                                <div class="form-text">
                                    <small class="text-muted">Format: +1234567890 (include country code)</small>
                                </div>
                                @error('to')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="message_text" class="form-label">Message Text *</label>
                                <textarea class="form-control @error('message') is-invalid @enderror"
                                          id="message_text" name="message" rows="4"
                                          placeholder="Enter your message text here..." required>{{ old('message') }}</textarea>
                                <div class="form-text">
                                    <small class="text-muted">Maximum 4096 characters</small>
                                </div>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    Send Text Message
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Quick Tips -->
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-lightbulb me-2"></i>
                    Quick Tips
                </h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Use templates for marketing messages
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Text messages for quick notifications
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Always include country code (+1, +44, etc.)
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Test with your own number first
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Auto-format phone numbers
document.getElementById('recipients').addEventListener('input', function() {
    let value = this.value;
    // Remove all non-digit characters except + and commas
    value = value.replace(/[^\d+,]/g, '');
    this.value = value;
});

document.getElementById('text_recipient').addEventListener('input', function() {
    let value = this.value;
    // Remove all non-digit characters except +
    value = value.replace(/[^\d+]/g, '');
    this.value = value;
});

// Character counter for message text
document.getElementById('message_text').addEventListener('input', function() {
    const maxLength = 4096;
    const currentLength = this.value.length;
    const remaining = maxLength - currentLength;

    // Update character count display
    let counter = document.getElementById('char-counter');
    if (!counter) {
        counter = document.createElement('div');
        counter.id = 'char-counter';
        counter.className = 'form-text text-end';
        this.parentNode.appendChild(counter);
    }

    counter.textContent = `${currentLength}/${maxLength} characters`;

    if (remaining < 100) {
        counter.className = 'form-text text-end text-warning';
    } else if (remaining < 0) {
        counter.className = 'form-text text-end text-danger';
    } else {
        counter.className = 'form-text text-end';
    }
});

// Form submission with loading state
document.getElementById('templateMessageForm').addEventListener('submit', function() {
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending...';
    submitBtn.disabled = true;
});

document.getElementById('textMessageForm').addEventListener('submit', function() {
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending...';
    submitBtn.disabled = true;
});
</script>
@endsection
