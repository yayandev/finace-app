@extends('layouts.app')

@section('title', "Roles List")

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
            <h5 class="mb-0">Roles Management</h5>
            <a href="{{ route('roles.create') }}" class="btn btn-primary">Create Role</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Permissions</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roles as $role)
                            <tr>
                                <td>{{ $role->name }}</td>
                                <td>
                                    {{-- @foreach($role->permissions as $permission)
                                        <span class="badge bg-primary">{{ $permission->name }}</span>
                                    @endforeach --}}
                                    <span class="badge bg-primary">{{ $role->permissions->count() }}</span>

                                </td>
                                <td>
                                    <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('roles.destroy', $role) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
