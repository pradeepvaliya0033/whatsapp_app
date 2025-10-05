@extends('layouts.app')

@section('title', 'Messages - WhatsApp Business Provider')
@section('page-title', 'Message History')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="h4 mb-0">Message History</h2>
        <p class="text-muted mb-0">WhatsApp message tracking is not available in simplified mode</p>
    </div>
    <div class="btn-group">
        <a href="{{ route('whatsapp.send') }}" class="btn btn-success">
            <i class="fab fa-whatsapp me-2"></i>
            Send Message
        </a>
    </div>
</div>

<!-- Information Card -->
<div class="card">
    <div class="card-body text-center py-5">
        <i class="fas fa-info-circle fa-3x text-info mb-3"></i>
        <h4 class="mb-3">Message Tracking Disabled</h4>
        <p class="text-muted mb-4">
            In simplified mode, message tracking has been disabled to streamline the WhatsApp integration.
            Messages are sent directly to WhatsApp without local storage.
        </p>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="alert alert-info">
                    <h6 class="alert-heading">
                        <i class="fas fa-lightbulb me-2"></i>
                        What's Available:
                    </h6>
                    <ul class="mb-0 text-start">
                        <li>Send template messages</li>
                        <li>Send text messages</li>
                        <li>Create and manage templates</li>
                        <li>Direct WhatsApp API integration</li>
                    </ul>
                </div>
            </div>
        </div>
        <a href="{{ route('whatsapp.send') }}" class="btn btn-primary btn-lg">
            <i class="fab fa-whatsapp me-2"></i>
            Start Sending Messages
        </a>
    </div>
</div>
@endsection
