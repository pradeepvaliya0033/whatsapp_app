@extends('layouts.app')

@section('title', 'Database Test - WhatsApp Business Provider')
@section('page-title', 'Database Relationship Test')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Database Relationship Test</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <h6>Entities</h6>
                        <ul class="list-group">
                            @forelse(\App\Models\EntityMaster::with('providerMappings')->get() as $entity)
                                <li class="list-group-item">
                                    <strong>{{ $entity->name }}</strong>
                                    <br>
                                    <small class="text-muted">Mappings: {{ $entity->providerMappings->count() }}</small>
                                </li>
                            @empty
                                <li class="list-group-item">No entities found</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h6>Providers</h6>
                        <ul class="list-group">
                            @forelse(\App\Models\ProviderMaster::with('providerMappings')->get() as $provider)
                                <li class="list-group-item">
                                    <strong>{{ $provider->name }}</strong>
                                    <br>
                                    <small class="text-muted">Type: {{ $provider->provider_type }}</small>
                                    <br>
                                    <small class="text-muted">Mappings: {{ $provider->providerMappings->count() }}</small>
                                </li>
                            @empty
                                <li class="list-group-item">No providers found</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h6>Mappings</h6>
                        <ul class="list-group">
                            @forelse(\App\Models\EntityProviderMapping::with(['entity', 'provider'])->get() as $mapping)
                                <li class="list-group-item">
                                    <strong>{{ $mapping->entity->name ?? 'Unknown' }}</strong>
                                    <br>
                                    <small class="text-muted">→ {{ $mapping->provider->name ?? 'Unknown' }}</small>
                                    <br>
                                    <small class="text-muted">Type: {{ $mapping->usage_type }}</small>
                                </li>
                            @empty
                                <li class="list-group-item">No mappings found</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <div class="mt-4">
                    <h6>Quick Actions</h6>
                    <div class="d-grid gap-2 d-md-flex">
                        <a href="{{ route('entities.index') }}" class="btn btn-primary">Manage Entities</a>
                        <a href="{{ route('providers.index') }}" class="btn btn-info">Manage Providers</a>
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
