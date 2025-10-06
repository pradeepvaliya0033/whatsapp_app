@extends('layouts.app')

@section('title', 'Add Contact')
@section('page-title', 'Add Contact')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('contacts.store') }}" onsubmit="showLoading($(this))">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" placeholder="+15551234567" value="{{ old('phone') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                        </div>
                        <div class="col-md-6"></div>

                        <div class="col-12">
                            <label class="form-label">Tags</label>
                            <input type="text" name="tags[]" class="form-control mb-2" placeholder="e.g., VIP">
                            <div id="more-tags"></div>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addTagField()"><i class="fas fa-plus"></i> Add Tag</button>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Custom Attributes</label>
                            <div id="attributes-container">
                                <div class="row g-2 mb-2">
                                    <div class="col-md-5">
                                        <input type="text" name="attributes[key][]" class="form-control" placeholder="Key (e.g., source)">
                                    </div>
                                    <div class="col-md-7">
                                        <input type="text" name="attributes[value][]" class="form-control" placeholder="Value (e.g., website)">
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addAttributeRow()"><i class="fas fa-plus"></i> Add Attribute</button>
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-between align-items-center">
                        <a href="{{ route('contacts.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save Contact</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
function addTagField() {
    const div = document.createElement('div');
    div.className = 'mb-2';
    div.innerHTML = '<input type="text" name="tags[]" class="form-control" placeholder="Add tag">';
    document.getElementById('more-tags').appendChild(div);
}

function addAttributeRow() {
    const container = document.getElementById('attributes-container');
    const row = document.createElement('div');
    row.className = 'row g-2 mb-2';
    row.innerHTML = `
        <div class="col-md-5">
            <input type="text" name="attributes[key][]" class="form-control" placeholder="Key">
        </div>
        <div class="col-md-7">
            <input type="text" name="attributes[value][]" class="form-control" placeholder="Value">
        </div>
    `;
    container.appendChild(row);
}
</script>
@endsection
@endsection


