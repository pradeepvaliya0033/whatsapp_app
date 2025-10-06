@extends('layouts.app')

@section('title', 'Contacts')
@section('page-title', 'Contacts')

@section('content')
<div class="row">
    <div class="col-12 mb-3 d-flex justify-content-between align-items-center">
        <h4 class="mb-0"><i class="fas fa-address-book me-2"></i>Your Contacts</h4>
        <a href="{{ route('contacts.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Add Contact</a>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" class="row g-2 mb-3">
                    <div class="col-md-4">
                        <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search by name, phone, or email">
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="tag" value="{{ request('tag') }}" class="form-control" placeholder="Filter by tag">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-secondary w-100" type="submit"><i class="fas fa-search me-1"></i> Search</button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Tags</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($contacts as $contact)
                                <tr>
                                    <td>{{ $contact->name }}</td>
                                    <td>{{ $contact->phone }}</td>
                                    <td>{{ $contact->email ?? '-' }}</td>
                                    <td>
                                        @if(is_array($contact->tags))
                                            @foreach($contact->tags as $tag)
                                                <span class="badge bg-secondary me-1">{{ $tag }}</span>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('contacts.edit', $contact->uuid) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('contacts.destroy', $contact->uuid) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this contact?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No contacts yet. Click "Add Contact" to create one.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $contacts->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection


