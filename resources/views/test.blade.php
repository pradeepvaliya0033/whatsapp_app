@extends('layouts.app')

@section('title', 'Test - WhatsApp Business Provider')
@section('page-title', 'Test Page')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">System Test</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Database Test</h6>
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Entities
                                <span class="badge bg-primary rounded-pill">{{ \App\Models\EntityMaster::count() }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Providers
                                <span class="badge bg-primary rounded-pill">{{ \App\Models\ProviderMaster::count() }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Messages
                                <span class="badge bg-primary rounded-pill">{{ \App\Models\MessageRequest::count() }}</span>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Route Test</h6>
                        <div class="d-grid gap-2">
                            <a href="{{ route('entities.index') }}" class="btn btn-outline-primary">Test Entities</a>
                            <a href="{{ route('providers.index') }}" class="btn btn-outline-info">Test Providers</a>
                            <a href="{{ route('whatsapp.send') }}" class="btn btn-outline-success">Test WhatsApp</a>
                            <a href="{{ route('templates.index') }}" class="btn btn-outline-warning">Test Templates</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
