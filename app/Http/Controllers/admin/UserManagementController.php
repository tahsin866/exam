<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PermissionModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::admins()
            ->with(['roles', 'permissions']);

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Search by phone
        if ($request->filled('phone')) {
            $query->where('phone', 'like', '%' . $request->phone . '%');
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        $users = $query->latest()->paginate(10);

        return Inertia::render('admin/UserManagement/Index', [
            'users' => $users
        ]);
    }

    public function create()
    {
        $roles = Role::all();
        $modules = PermissionModule::with('permissions')
            ->active()
            ->ordered()
            ->get();

        return Inertia::render('admin/UserManagement/Create', [
            'roles' => $roles,
            'modules' => $modules
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => 'nullable|string|max:15',
            'nid' => 'nullable|string|max:20',
            'brn' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'role' => 'required|exists:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $profileImagePath = null;
        if ($request->hasFile('profile_image')) {
            $profileImagePath = $request->file('profile_image')->store('profile-images', 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => 'admin',
            'phone' => $request->phone,
            'nid' => $request->nid,
            'brn' => $request->brn,
            'address' => $request->address,
            'profile_image' => $profileImagePath,
            'is_active' => true,
        ]);

        // Assign role
        if ($request->role) {
            $user->assignRole($request->role);
        }

        // Assign permissions if provided
        if ($request->permissions && count($request->permissions) > 0) {
            $user->givePermissionTo($request->permissions);
        }

        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log('New admin user created');

        return redirect()->route('admin.users.index')
            ->with('success', 'নতুন এডমিন ইউজার সফলভাবে তৈরি হয়েছে');
    }

    public function show(User $user)
    {
        $user->load(['roles', 'permissions']);
        
        return Inertia::render('admin/UserManagement/Show', [
            'user' => $user
        ]);
    }

    public function edit(User $user)
    {
        // Debug: Check if user exists
        if (!$user->exists) {
            abort(404, 'User not found');
        }
        
        $user->load(['roles', 'permissions']);
        $roles = Role::all();
        $modules = PermissionModule::with('permissions')
            ->active()
            ->ordered()
            ->get();

        return Inertia::render('admin/UserManagement/Edit', [
            'user' => $user,
            'roles' => $roles,
            'modules' => $modules,
            'userPermissions' => $user->permissions->pluck('name')->toArray(),
            'userRoles' => $user->roles->pluck('name')->toArray()
        ]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|confirmed|min:8',
            'phone' => 'nullable|string|max:15',
            'nid' => 'nullable|string|max:20',
            'brn' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'role' => 'required|exists:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_active' => 'boolean'
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'nid' => $request->nid,
            'brn' => $request->brn,
            'address' => $request->address,
            'is_active' => $request->is_active ?? true,
        ];

        // Update password if provided
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $updateData['profile_image'] = $request->file('profile_image')->store('profile-images', 'public');
        }

        $user->update($updateData);

        // Update role
        $user->syncRoles([$request->role]);

        // Update permissions
        $user->syncPermissions($request->permissions ?? []);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log('Admin user updated');

        return redirect()->route('admin.users.index')
            ->with('success', 'এডমিন ইউজার সফলভাবে আপডেট হয়েছে');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'আপনি নিজেকে ডিলিট করতে পারবেন না');
        }

        // Delete profile image if exists
        if ($user->profile_image) {
            Storage::disk('public')->delete($user->profile_image);
        }

        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log('Admin user deleted');

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'এডমিন ইউজার সফলভাবে ডিলিট হয়েছে');
    }

    public function toggleStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'আপনি নিজের স্ট্যাটাস পরিবর্তন করতে পারবেন না');
        }

        $user->update(['is_active' => !$user->is_active]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log('Admin user status toggled');

        return back()->with('success', 'ইউজার স্ট্যাটাস পরিবর্তন হয়েছে');
    }
}
