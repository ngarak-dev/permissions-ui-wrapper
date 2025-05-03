<div class="p-6 bg-white rounded-lg shadow-md">
    <h1 class="text-2xl font-bold mb-6">Role Permission Matrix</h1>

    <!-- Filters -->
    <div class="flex flex-wrap gap-4 mb-6">
        <div class="w-full md:w-auto flex-1">
            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search Permissions</label>
            <input type="text" wire:model.debounce.300ms="searchTerm" placeholder="Search by name..."
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="w-full md:w-auto flex-1">
            <label for="group" class="block text-sm font-medium text-gray-700 mb-1">Filter by Group</label>
            <select wire:model="selectedGroup"
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Groups</option>
                @foreach($groups as $group)
                <option value="{{ $group }}">{{ $group }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-full md:w-auto flex items-end">
            <button wire:click="clearFilters"
                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                Clear Filters
            </button>
        </div>
    </div>

    @if($showSuccessMessage)
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
        <p>{{ $successMessage }}</p>
    </div>
    @endif

    <div class="overflow-x-auto">
        <form wire:submit.prevent="updatePermissions">
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr>
                        <th class="sticky left-0 bg-gray-100 z-10 border-b p-4">Permissions</th>
                        @foreach($roles as $role)
                        <th class="border-b p-4 text-center">
                            {{ $role->name }}
                            <div class="mt-2">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox"
                                        wire:click="toggleAll({{ $role->id }}, $event.target.checked)"
                                        class="form-checkbox h-5 w-5 text-blue-600"
                                        @if(count($checkedPermissions[$role->id] ?? []) ===
                                    count(app(\Spatie\Permission\Models\Permission::class)->all())) checked @endif
                                    >
                                    <span class="ml-2 text-sm">All</span>
                                </label>
                            </div>
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse($permissionsByGroup as $group => $permissions)
                    <tr class="bg-gray-50">
                        <td class="sticky left-0 bg-gray-50 font-bold p-4 border-b text-blue-700">
                            {{ $group ?: 'Ungrouped' }}
                        </td>
                        @foreach($roles as $role)
                        <td class="p-4 border-b text-center">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox"
                                    wire:click="toggleGroup({{ $role->id }}, '{{ $group }}', $event.target.checked)"
                                    class="form-checkbox h-5 w-5 text-blue-600"
                                    @if($this->getHasAllGroupPermissionsProperty($role->id, $group)) checked
                                @elseif($this->getHasAnyGroupPermissionsProperty($role->id, $group)) indeterminate
                                @endif
                                >
                            </label>
                        </td>
                        @endforeach
                    </tr>
                    @foreach($permissions as $permission)
                    <tr>
                        <td class="sticky left-0 bg-white p-4 border-b">
                            {{ $permission->name }}
                        </td>
                        @foreach($roles as $role)
                        <td class="p-4 border-b text-center">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox"
                                    wire:model="checkedPermissions.{{ $role->id }}.{{ $permission->id }}"
                                    class="form-checkbox h-5 w-5 text-blue-600">
                            </label>
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                    @empty
                    <tr>
                        <td colspan="{{ count($roles) + 1 }}" class="p-4 text-center text-gray-500">
                            No permissions found. Try adjusting your filters.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    <!-- Toast JS for notifications -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.addEventListener('show-toast', event => {
                // You can implement a toast notification system here
                // or use a library like Toastify or SweetAlert2
                alert(event.detail.message);
            });
        });
    </script>
</div>