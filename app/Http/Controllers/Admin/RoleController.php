<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of roles.
     */
    public function index()
    {
        $roles = Role::withCount(['users', 'permissions'])->orderBy('name')->get();
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new role.
     */
    public function create()
    {
        $permissions = Permission::orderBy('group')->orderBy('name')->get()->groupBy('group');
        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created role.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'description' => 'nullable|string|max:500',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,slug',
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'slug' => \Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
        ]);

        if (!empty($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role created successfully.');
    }

    /**
     * Show the form for editing a role.
     */
    public function edit(Role $role)
    {
        $permissions = Permission::orderBy('group')->orderBy('name')->get()->groupBy('group');
        $rolePermissions = $role->permissions()->pluck('slug')->toArray();
        
        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update the specified role.
     */
    public function update(Request $request, Role $role)
    {
        // Prevent editing super-admin role name/slug
        if ($role->slug === 'super-admin') {
            $validated = $request->validate([
                'description' => 'nullable|string|max:500',
                'permissions' => 'array',
                'permissions.*' => 'exists:permissions,slug',
            ]);
        } else {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
                'description' => 'nullable|string|max:500',
                'permissions' => 'array',
                'permissions.*' => 'exists:permissions,slug',
            ]);

            $role->update([
                'name' => $validated['name'],
                'slug' => \Str::slug($validated['name']),
                'description' => $validated['description'] ?? null,
            ]);
        }

        // Super admin always has all permissions
        if ($role->slug === 'super-admin') {
            $role->syncPermissions(Permission::pluck('slug')->toArray());
        } else {
            $role->syncPermissions($validated['permissions'] ?? []);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified role.
     */
    public function destroy(Role $role)
    {
        // Prevent deleting super-admin and default roles
        if ($role->slug === 'super-admin') {
            return back()->with('error', 'Cannot delete the Super Admin role.');
        }

        if ($role->is_default) {
            return back()->with('error', 'Cannot delete the default role.');
        }

        // Move users to default role or null
        $defaultRole = Role::getDefault();
        $role->users()->update(['role_id' => $defaultRole?->id]);

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role deleted successfully.');
    }
}
