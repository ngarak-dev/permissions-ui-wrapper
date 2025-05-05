<?php

namespace NgarakDev\PermissionsUiWrapper\Http\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class RolePermissionMatrix extends Component
{
    use AuthorizesRequests;

    public $roles = [];
    public $permissionsByGroup = [];
    public $checkedPermissions = [];
    public $showSuccessMessage = false;
    public $successMessage = '';
    public $searchTerm = '';
    public $selectedGroup = '';
    public $groups = [];

    protected $listeners = ['refresh' => '$refresh'];

    public function mount()
    {
        $this->authorize('manage permissions');
        $this->loadRoles();
        $this->loadPermissions();
        $this->initializeCheckedPermissions();
        $this->loadGroups();
    }

    public function render()
    {
        return view('permission-wrapper::livewire.role-permission-matrix');
    }

    public function loadRoles()
    {
        $this->roles = Role::orderBy('name')->get();
    }

    public function loadPermissions()
    {
        $query = Permission::query()
            ->orderBy('group')
            ->orderBy('name');

        // Apply search filter if provided
        if ($this->searchTerm) {
            $query->where('name', 'like', "%{$this->searchTerm}%");
        }

        // Filter by group if selected
        if ($this->selectedGroup) {
            $query->where('group', $this->selectedGroup);
        }

        $permissions = $query->get();

        // Group permissions by their group
        $this->permissionsByGroup = $permissions->groupBy('group');
    }

    public function loadGroups()
    {
        $configGroups = config('permissions-ui.permission_groups', []);
        $dbGroups = Permission::distinct('group')->pluck('group')->filter()->toArray();

        $this->groups = collect($configGroups)
            ->keys()
            ->merge($dbGroups)
            ->unique()
            ->filter()
            ->toArray();
    }

    public function initializeCheckedPermissions()
    {
        $this->checkedPermissions = [];

        foreach ($this->roles as $role) {
            $permissionIds = $role->permissions->pluck('id')->toArray();
            $this->checkedPermissions[$role->id] = array_fill_keys($permissionIds, true);
        }
    }

    public function togglePermission($roleId, $permissionId)
    {
        $this->authorize('manage permissions');

        if (!isset($this->checkedPermissions[$roleId][$permissionId])) {
            $this->checkedPermissions[$roleId][$permissionId] = true;
        } else {
            unset($this->checkedPermissions[$roleId][$permissionId]);
        }
    }

    public function toggleAll($roleId, $value)
    {
        $this->authorize('manage permissions');

        $allPermissionIds = Permission::pluck('id')->toArray();

        if ($value) {
            // Check all permissions
            foreach ($allPermissionIds as $permissionId) {
                $this->checkedPermissions[$roleId][$permissionId] = true;
            }
        } else {
            // Uncheck all permissions
            $this->checkedPermissions[$roleId] = [];
        }
    }

    public function toggleGroup($roleId, $group, $value)
    {
        $this->authorize('manage permissions');

        $permissionIds = Permission::where('group', $group)->pluck('id')->toArray();

        foreach ($permissionIds as $permissionId) {
            if ($value) {
                $this->checkedPermissions[$roleId][$permissionId] = true;
            } else {
                unset($this->checkedPermissions[$roleId][$permissionId]);
            }
        }
    }

    public function updatePermissions()
    {
        $this->authorize('manage permissions');

        foreach ($this->roles as $role) {
            // Get selected permission IDs for this role
            $permissionIds = array_keys($this->checkedPermissions[$role->id] ?? []);

            // Use Spatie's syncPermissions method with an array of IDs
            // This is supported by the package and avoids the collection issue
            $role->syncPermissions($permissionIds);
        }

        $this->showSuccessAlert('Permissions updated successfully');
    }

    public function updatedSearchTerm()
    {
        $this->loadPermissions();
    }

    public function updatedSelectedGroup()
    {
        $this->loadPermissions();
    }

    public function clearFilters()
    {
        $this->searchTerm = '';
        $this->selectedGroup = '';
        $this->loadPermissions();
    }

    private function showSuccessAlert($message)
    {
        $this->successMessage = $message;
        $this->showSuccessMessage = true;

        $this->dispatchBrowserEvent('show-toast', [
            'message' => $message,
            'type' => 'success'
        ]);
    }

    public function getHasAllGroupPermissionsProperty($roleId, $group)
    {
        $groupPermissionIds = Permission::where('group', $group)->pluck('id')->toArray();
        $rolePermissions = $this->checkedPermissions[$roleId] ?? [];

        foreach ($groupPermissionIds as $permissionId) {
            if (!isset($rolePermissions[$permissionId])) {
                return false;
            }
        }

        return !empty($groupPermissionIds);
    }

    public function getHasAnyGroupPermissionsProperty($roleId, $group)
    {
        $groupPermissionIds = Permission::where('group', $group)->pluck('id')->toArray();
        $rolePermissions = $this->checkedPermissions[$roleId] ?? [];

        foreach ($groupPermissionIds as $permissionId) {
            if (isset($rolePermissions[$permissionId])) {
                return true;
            }
        }

        return false;
    }
}
