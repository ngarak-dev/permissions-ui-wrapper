<?php

namespace NgarakDev\PermissionsUiWrapper\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = Auth::user();
            if (!$user) {
                abort(403, 'Unauthorized action.');
            }

            // The permissions_manager_role in config allows management
            $permissionsManagerRole = config('permissions-ui.permissions_manager_role');

            // Check if the user has the manager role or specific permission
            $canManagePermissions = false;

            if ($permissionsManagerRole && method_exists($user, 'hasRole')) {
                $canManagePermissions = $user->hasRole($permissionsManagerRole);
            }

            if (!$canManagePermissions && method_exists($user, 'can')) {
                $canManagePermissions = $user->can('manage permissions');
            }

            if (!$canManagePermissions) {
                abort(403, 'Unauthorized action.');
            }

            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $query = Permission::query();

        // Apply search filter if provided
        if ($request->has('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        // Get paginated results
        $permissions = $query->paginate(10);

        return view($this->getViewPath('index'), compact('permissions'));
    }

    public function create()
    {
        $groups = config('permissions-ui.permission_groups', []);
        return view($this->getViewPath('create'), compact('groups'));
    }

    public function store(Request $request)
    {
        $messages = config('permissions-ui.validation_messages', []);

        $request->validate([
            'name' => 'required|unique:permissions,name',
            'group' => 'nullable|string',
        ], $messages);

        Permission::create([
            'name' => $request->name,
            'group' => $request->group,
        ]);

        return redirect()->route('permissions.index')
            ->with('success', 'Permission created successfully');
    }

    public function edit(Permission $permission)
    {
        $groups = config('permissions-ui.permission_groups', []);
        return view($this->getViewPath('edit'), compact('permission', 'groups'));
    }

    public function update(Request $request, Permission $permission)
    {
        $messages = config('permissions-ui.validation_messages', []);

        $request->validate([
            'name' => 'required|unique:permissions,name,' . $permission->id,
            'group' => 'nullable|string',
        ], $messages);

        $permission->update([
            'name' => $request->name,
            'group' => $request->group,
        ]);

        return redirect()->route('permissions.index')
            ->with('success', 'Permission updated successfully');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();

        return redirect()->route('permissions.index')
            ->with('success', 'Permission deleted successfully');
    }

    // Role methods
    public function indexRoles(Request $request)
    {
        $query = Role::with('permissions');

        // Apply search filter if provided
        if ($request->has('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        // Get paginated results
        $roles = $query->paginate(10);

        return view($this->getViewPath('roles.index'), compact('roles'));
    }

    public function createRole()
    {
        $permissions = Permission::all();
        return view($this->getViewPath('roles.create'), compact('permissions'));
    }

    public function storeRole(Request $request)
    {
        $messages = config('permissions-ui.validation_messages', []);

        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ], $messages);

        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('roles.index')
            ->with('success', 'Role created successfully');
    }

    public function editRole(Role $role)
    {
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view($this->getViewPath('roles.edit'), compact('role', 'permissions', 'rolePermissions'));
    }

    public function updateRole(Request $request, Role $role)
    {
        $messages = config('permissions-ui.validation_messages', []);

        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ], $messages);

        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('roles.index')
            ->with('success', 'Role updated successfully');
    }

    public function destroyRole(Role $role)
    {
        $role->delete();

        return redirect()->route('roles.index')
            ->with('success', 'Role deleted successfully');
    }

    // Helper method to determine view path based on config
    private function getViewPath($view)
    {
        $framework = config('permissions-ui.ui_framework', 'bootstrap');
        return "permissions-ui::{$framework}.{$view}";
    }
}
