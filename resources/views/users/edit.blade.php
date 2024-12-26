@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
   <div class="card">
    <div class="card-header">
    <h4 class="fw-bold">Edit User</h4>
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
    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ $user->name }}" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control" value="{{ $user->email }}" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" name="password" class="form-control">
            <small class="form-text text-muted">Leave blank if you don't want to change the password</small>
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
        </div>
        <div class="mb-3 form-floating form-floating-outline">
            <div class="select2-primary">
              <select id="roles" name="roles[]" class="select2 form-select" >
                @foreach ($roles as $role)
                <option value="{{ $role->name }}" {{ $user->roles->contains($role->id) ? 'selected' : '' }}>{{ $role->name }}</option>
                @endforeach
              </select>
            </div>
            <label for="roles">Roles</label>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
    </div>
   </div>
</div>
@endsection
@push('css')
<link rel="stylesheet" href="/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
<link rel="stylesheet" href="/assets/vendor/libs/typeahead-js/typeahead.css" />
<link rel="stylesheet" href="/assets/vendor/libs/select2/select2.css" />
<link rel="stylesheet" href="/assets/vendor/libs/tagify/tagify.css" />
<link rel="stylesheet" href="/assets/vendor/libs/bootstrap-select/bootstrap-select.css" />
<link rel="stylesheet" href="/assets/vendor/libs/typeahead-js/typeahead.css" />
@endpush
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
