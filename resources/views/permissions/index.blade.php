@extends('layouts.app')

@section('title', 'Permissions')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between flex-wrap">
                <h4 class="fw-bold">Permissions</h4>
                <a href="{{ route('permissions.create') }}" class="btn btn-primary mb-3">Create Permission</a>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Roles</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($permissions as $permission)
                            <tr>
                                <td>{{ $permission->name }}</td>
                                <td>
                                    @foreach ($permission->roles as $role)
                                        <span class="badge bg-secondary">{{ $role->name }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    <a href="{{ route('permissions.edit', $permission->id) }}"
                                        class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm"
                                            onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $permissions->links("pagination::bootstrap-5") }}
            </div>
        </div>
    </div>
@endsection
