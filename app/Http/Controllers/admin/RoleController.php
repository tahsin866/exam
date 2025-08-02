<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PermissionModule;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->paginate(10);

        return Inertia::render('admin/Roles/Index', [
            'roles' => $roles
        ]);
    }

    public function create()
    {
        $modules = PermissionModule::with('permissions')
            ->active()
            ->ordered()
            ->get();

        return Inertia::render('admin/Roles/Create', [
            'modules' => $modules
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name'
        ]);

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web'
        ]);

        if ($request->permissions) {
            $role->givePermissionTo($request->permissions);
        }

        activity()
            ->causedBy(request()->user())
            ->performedOn($role)
            ->log('New role created');

        return redirect()->route('admin.roles.index')
            ->with('success', 'নতুন রোল সফলভাবে তৈরি হয়েছে');
    }

    public function show(Role $role)
    {
        $role->load('permissions');

        return Inertia::render('admin/Roles/Show', [
            'role' => $role
        ]);
    }

    public function edit(Role $role)
    {
        $role->load('permissions');
        $modules = PermissionModule::with('permissions')
            ->active()
            ->ordered()
            ->get();

        return Inertia::render('admin/Roles/Edit', [
            'role' => $role,
            'modules' => $modules,
            'rolePermissions' => $role->permissions->pluck('name')->toArray()
        ]);
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name'
        ]);

        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions ?? []);

        activity()
            ->causedBy(request()->user())
            ->performedOn($role)
            ->log('Role updated');

        return redirect()->route('admin.roles.index')
            ->with('success', 'রোল সফলভাবে আপডেট হয়েছে');
    }

    public function destroy(Role $role)
    {
        // Check if role is being used by any user
        if ($role->users()->count() > 0) {
            return back()->with('error', 'এই রোলটি ব্যবহার হচ্ছে, তাই ডিলিট করা যাবে না');
        }

        // Prevent deletion of default roles
        $defaultRoles = ['super-admin', 'assistant-super-admin', 'board-admin'];
        if (in_array($role->name, $defaultRoles)) {
            return back()->with('error', 'ডিফল্ট রোল ডিলিট করা যাবে না');
        }

        activity()
            ->causedBy(request()->user())
            ->performedOn($role)
            ->log('Role deleted');

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'রোল সফলভাবে ডিলিট হয়েছে');
    }
}
