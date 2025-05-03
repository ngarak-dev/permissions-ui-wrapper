<?php

namespace NgarakDev\PermissionsUiWrapper\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PermissionManager extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    public $permission;
    public $permissionId;
    public $name;
    public $group;
    public $guard_name = 'web';
    public $modalTitle;
    public $showModal = false;
    public $confirmingDelete = false;
    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';

    protected $rules = [
        'name' => 'required|string|max:255',
        'group' => 'nullable|string|max:255',
        'guard_name' => 'required|string|max:255',
    ];

    public function mount()
    {
        $this->permission = new Permission();
    }

    public function render()
    {
        $this->authorize('manage permissions');

        $permissions = Permission::where('name', 'like', "%{$this->search}%")
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        $groups = config('permissions-ui.permission_groups', []);

        return view('permission-wrapper::livewire.permission-manager', [
            'permissions' => $permissions,
            'groups' => $groups,
        ]);
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function create()
    {
        $this->resetValidation();
        $this->reset(['permissionId', 'name', 'group', 'guard_name']);
        $this->guard_name = 'web';
        $this->modalTitle = 'Create Permission';
        $this->showModal = true;
    }

    public function edit(Permission $permission)
    {
        $this->resetValidation();
        $this->permissionId = $permission->id;
        $this->name = $permission->name;
        $this->group = $permission->group;
        $this->guard_name = $permission->guard_name;
        $this->modalTitle = 'Edit Permission';
        $this->showModal = true;
    }

    public function confirmDelete(Permission $permission)
    {
        $this->permission = $permission;
        $this->confirmingDelete = true;
    }

    public function delete()
    {
        $this->authorize('manage permissions');
        $this->permission->delete();
        $this->confirmingDelete = false;
        $this->emit('notify', 'Permission deleted successfully.');
    }

    public function save()
    {
        $this->authorize('manage permissions');
        $this->validate();

        if ($this->permissionId) {
            $permission = Permission::findById($this->permissionId);
            $permission->update([
                'name' => $this->name,
                'group' => $this->group,
                'guard_name' => $this->guard_name,
            ]);
            $message = 'Permission updated successfully.';
        } else {
            Permission::create([
                'name' => $this->name,
                'group' => $this->group,
                'guard_name' => $this->guard_name,
            ]);
            $message = 'Permission created successfully.';
        }

        $this->showModal = false;
        $this->emit('notify', $message);
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->confirmingDelete = false;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
