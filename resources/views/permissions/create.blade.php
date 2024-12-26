@extends('layouts.app')

@section('title', 'Create Permission')
@push('css')
<link rel="stylesheet" href="/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
<link rel="stylesheet" href="/assets/vendor/libs/typeahead-js/typeahead.css" />
<link rel="stylesheet" href="/assets/vendor/libs/select2/select2.css" />
<link rel="stylesheet" href="/assets/vendor/libs/tagify/tagify.css" />
<link rel="stylesheet" href="/assets/vendor/libs/bootstrap-select/bootstrap-select.css" />
<link rel="stylesheet" href="/assets/vendor/libs/typeahead-js/typeahead.css" />
@endpush
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header">
                <h4 class="fw-bold">Create Permission</h4>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('permissions.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Permission Name</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}"
                            required>
                    </div>
                    <div class="mb-3 form-floating form-floating-outline">
                        <div class="select2-primary">
                          <select id="roles" name="roles[]" class="select2 form-select" multiple>
                            @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                          </select>
                        </div>
                        <label for="roles">Roles</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a href="{{ route('permissions.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection



@push('scripts')
    <script src="/assets/vendor/libs/select2/select2.js"></script>
    <script src="/assets/vendor/libs/tagify/tagify.js"></script>
    <script src="/assets/vendor/libs/bootstrap-select/bootstrap-select.js"></script>
    <script src="/assets/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="/assets/vendor/libs/bloodhound/bloodhound.js"></script>

    <script src="/assets/js/forms-selects.js"></script>
    <script src="/assets/js/forms-tagify.js"></script>
    <script src="/assets/js/forms-typeahead.js"></script>
@endpush
