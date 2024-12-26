<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        // with roles and paginate 10
        $permissions = Permission::with('roles')->paginate(10);
        return view('permissions.index', compact('permissions'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('permissions.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:permissions,name']);

        $permission = Permission::create(['name' => $request->name]);

        if ($request->has('roles')) {
            $permission->roles()->sync($request->roles);
        }

        return redirect()->route('permissions.index')->with('success', 'Permission created successfully');
    }

    public function edit(Permission $permission)
    {
        $roles = Role::all();
        return view('permissions.edit', compact('permission', 'roles'));
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name,' . $permission->id
        ]);

        $permission->update(['name' => $request->name]);

        if ($request->has('roles')) {
            $permission->roles()->sync($request->roles);
        }

        return redirect()->route('permissions.index')->with('success', 'Permission updated successfully');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect()->route('permissions.index')->with('success', 'Permission deleted successfully');
    }
}
