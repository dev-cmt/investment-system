<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    /**
     * Group permissions by module.
     */
    private function getPermissionGroups(): array
    {
        return [
            'Investment Posts' => [
                'view posts' => 'View posts',
                'create posts' => 'Create posts',
                'edit posts' => 'Edit posts',
                'delete posts' => 'Delete posts',
            ],
            'Investments' => [
                'view all investments' => 'View all investments',
                'view own investments' => 'View own investments',
                'create investments' => 'Create investments',
                'edit investments' => 'Edit investments',
                'delete investments' => 'Delete investments',
            ],
            'Payments' => [
                'view all payments' => 'View all payments',
                'view own payments' => 'View own payments',
                'create payments' => 'Create payments',
                'edit payments' => 'Edit payments',
                'delete payments' => 'Delete payments',
            ],
            'Withdrawals' => [
                'view all withdrawals' => 'View all withdrawals',
                'view own withdrawals' => 'View own withdrawals',
                'request withdrawal' => 'Request withdrawal',
                'manage withdrawals' => 'Manage withdrawals',
            ],
            'Users & Clients' => [
                'view users' => 'View users',
                'create users' => 'Create users',
                'edit users' => 'Edit users',
                'delete users' => 'Delete users',
            ],
            'Roles & Permissions' => [
                'view roles' => 'View roles',
                'create roles' => 'Create roles',
                'edit roles' => 'Edit roles',
                'delete roles' => 'Delete roles',
            ],
            'Settings' => [
                'view settings' => 'View settings',
                'edit settings' => 'Edit settings',
            ],
        ];
    }

    /**
     * Display a listing of roles.
     */
    public function index(): View
    {
        $roles = Role::with('permissions')->withCount('users')->get();
        return view('backend.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new role.
     */
    public function create(): View
    {
        $permissionGroups = $this->getPermissionGroups();
        return view('backend.roles.create', compact('permissionGroups'));
    }

    /**
     * Store a newly created role in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        $role = Role::create(['name' => $request->name, 'guard_name' => 'web']);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(Role $role): View
    {
        $permissionGroups = $this->getPermissionGroups();
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        return view('backend.roles.edit', compact('role', 'permissionGroups', 'rolePermissions'));
    }

    /**
     * Update the specified role in storage.
     */
    public function update(Request $request, Role $role): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        $role->update(['name' => $request->name]);

        $permissions = $request->input('permissions', []);
        $role->syncPermissions($permissions);

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified role from storage.
     */
    public function destroy(Role $role): RedirectResponse
    {
        if (in_array($role->name, ['superadmin', 'admin', 'investor'])) {
            return redirect()->route('roles.index')->with('error', 'Core system roles cannot be deleted.');
        }

        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }
}
