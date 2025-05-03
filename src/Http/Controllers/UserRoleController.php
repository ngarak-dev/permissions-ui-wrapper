<?php

namespace NgarakDev\PermissionsUiWrapper\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class UserRoleController extends Controller
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
            $canManageRoles = false;

            if ($permissionsManagerRole && method_exists($user, 'hasRole')) {
                $canManageRoles = $user->hasRole($permissionsManagerRole);
            }

            if (!$canManageRoles && method_exists($user, 'can')) {
                $canManageRoles = $user->can('manage roles');
            }

            if (!$canManageRoles) {
                abort(403, 'Unauthorized action.');
            }

            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $userModel = config('auth.providers.users.model');
        $query = $userModel::query();

        // Apply search filter if provided
        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('email', 'LIKE', '%' . $request->search . '%');
            });
        }

        // Get paginated results with roles
        $users = $query->paginate(10);

        $roles = Role::all();

        return view($this->getViewPath('users.index'), compact('users', 'roles'));
    }

    public function edit($userId)
    {
        $userModel = config('auth.providers.users.model');
        $user = $userModel::findOrFail($userId);

        $roles = Role::all();
        $userRoles = [];

        // Check if user has roles relationship
        if (method_exists($user, 'roles')) {
            $userRoles = $user->roles->pluck('name')->toArray();
        }

        return view($this->getViewPath('users.edit'), compact('user', 'roles', 'userRoles'));
    }

    public function update(Request $request, $userId)
    {
        $messages = config('permissions-ui.validation_messages', []);

        $request->validate([
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,name',
        ], $messages);

        $userModel = config('auth.providers.users.model');
        $user = $userModel::findOrFail($userId);

        // Check if user has syncRoles method
        if (method_exists($user, 'syncRoles')) {
            $user->syncRoles($request->roles ?? []);
        }

        return redirect()->route('user.roles.index')
            ->with('success', 'User roles updated successfully');
    }

    // Helper method to determine view path based on config
    private function getViewPath($view)
    {
        $framework = config('permissions-ui.ui_framework', 'bootstrap');

        // First check if the view exists in the permission-wrapper namespace (published views)
        $customNamespace = config('permissions-ui.views.namespace', 'permission-wrapper');
        $customView = "{$customNamespace}::{$framework}.{$view}";

        // Check if the custom view exists
        if (view()->exists($customView)) {
            return $customView;
        }

        // Fall back to the package view
        return "permissions-ui::{$framework}.{$view}";
    }
}
