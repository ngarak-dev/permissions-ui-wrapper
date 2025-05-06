@php
// Determine the installation type based on available routes
$hasStandardRoutes = !config('permissions-ui.disable_package_routes');
$hasLivewireRoutes = class_exists(\Livewire\Livewire::class);

// Get the current route for highlighting active links
$currentRoute = request()->route()->getName();

// Get the framework type
$isBootstrap = config('permissions-ui.ui_framework') === 'bootstrap';

// Get the route prefix
$prefix = config('permissions-ui.routes.prefix', 'permissions');

// Get the configured namespace
$configuredNamespace = config('permissions-ui.views.namespace', 'permission-wrapper');
@endphp

@if($isBootstrap)
<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('permissions.index') }}">Permissions Manager</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarPermissions"
            aria-controls="navbarPermissions" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarPermissions">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                @if($hasStandardRoutes)
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ str_contains($currentRoute, 'permissions.') && !str_contains($currentRoute, 'livewire') ? 'active' : '' }}"
                        href="#" id="permissionsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Permissions
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="permissionsDropdown">
                        <li><a class="dropdown-item {{ $currentRoute === 'permissions.index' ? 'active' : '' }}"
                                href="{{ route('permissions.index') }}">List</a></li>
                        <li><a class="dropdown-item {{ $currentRoute === 'permissions.create' ? 'active' : '' }}"
                                href="{{ route('permissions.create') }}">Create</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ str_contains($currentRoute, 'roles.') && !str_contains($currentRoute, 'livewire') ? 'active' : '' }}"
                        href="#" id="rolesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Roles
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="rolesDropdown">
                        <li><a class="dropdown-item {{ $currentRoute === 'roles.index' ? 'active' : '' }}"
                                href="{{ route('roles.index') }}">List</a></li>
                        <li><a class="dropdown-item {{ $currentRoute === 'roles.create' ? 'active' : '' }}"
                                href="{{ route('roles.create') }}">Create</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ str_contains($currentRoute, 'user.roles.') ? 'active' : '' }}"
                        href="{{ route('user.roles.index') }}">Users</a>
                </li>
                @endif

                @if($hasLivewireRoutes)
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ str_contains($currentRoute, 'livewire') ? 'active' : '' }}"
                        href="#" id="livewireDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Livewire
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="livewireDropdown">
                        <li><a class="dropdown-item {{ $currentRoute === 'permissions.livewire' ? 'active' : '' }}"
                                href="{{ route('permissions.livewire') }}">Permissions</a></li>
                        <li><a class="dropdown-item {{ $currentRoute === 'roles.matrix.livewire' ? 'active' : '' }}"
                                href="{{ route('roles.matrix.livewire') }}">Role Matrix</a></li>
                    </ul>
                </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
@else
<nav class="bg-white shadow mb-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('permissions.index') }}" class="text-blue-600 font-bold text-lg">
                        Permissions Manager
                    </a>
                </div>
                <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                    @if($hasStandardRoutes)
                    <div class="relative">
                        <button id="permissionsDropdown"
                            class="inline-flex items-center px-1 pt-1 border-b-2 {{ str_contains($currentRoute, 'permissions.') && !str_contains($currentRoute, 'livewire') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} text-sm font-medium">
                            Permissions
                            <svg class="ml-1 w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div id="permissionsMenu"
                            class="absolute z-10 hidden mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none">
                            <a href="{{ route('permissions.index') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $currentRoute === 'permissions.index' ? 'bg-gray-100' : '' }}">List</a>
                            <a href="{{ route('permissions.create') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $currentRoute === 'permissions.create' ? 'bg-gray-100' : '' }}">Create</a>
                        </div>
                    </div>
                    <div class="relative">
                        <button id="rolesDropdown"
                            class="inline-flex items-center px-1 pt-1 border-b-2 {{ str_contains($currentRoute, 'roles.') && !str_contains($currentRoute, 'livewire') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} text-sm font-medium">
                            Roles
                            <svg class="ml-1 w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div id="rolesMenu"
                            class="absolute z-10 hidden mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none">
                            <a href="{{ route('roles.index') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $currentRoute === 'roles.index' ? 'bg-gray-100' : '' }}">List</a>
                            <a href="{{ route('roles.create') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $currentRoute === 'roles.create' ? 'bg-gray-100' : '' }}">Create</a>
                        </div>
                    </div>
                    <a href="{{ route('user.roles.index') }}"
                        class="inline-flex items-center px-1 pt-1 border-b-2 {{ str_contains($currentRoute, 'user.roles.') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} text-sm font-medium">
                        Users
                    </a>
                    @endif

                    @if($hasLivewireRoutes)
                    <div class="relative">
                        <button id="livewireDropdown"
                            class="inline-flex items-center px-1 pt-1 border-b-2 {{ str_contains($currentRoute, 'livewire') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} text-sm font-medium">
                            Livewire
                            <svg class="ml-1 w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div id="livewireMenu"
                            class="absolute z-10 hidden mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none">
                            <a href="{{ route('permissions.livewire') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $currentRoute === 'permissions.livewire' ? 'bg-gray-100' : '' }}">Permissions</a>
                            <a href="{{ route('roles.matrix.livewire') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $currentRoute === 'roles.matrix.livewire' ? 'bg-gray-100' : '' }}">Role
                                Matrix</a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="-mr-2 flex items-center sm:hidden">
                <!-- Mobile menu button -->
                <button id="mobileMenuButton" type="button"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500"
                    aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div class="sm:hidden hidden" id="mobileMenu">
        <div class="pt-2 pb-3 space-y-1">
            @if($hasStandardRoutes)
            <a href="{{ route('permissions.index') }}"
                class="block pl-3 pr-4 py-2 border-l-4 {{ $currentRoute === 'permissions.index' ? 'border-blue-500 text-blue-700 bg-blue-50' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }} text-base font-medium">Permissions</a>
            <a href="{{ route('roles.index') }}"
                class="block pl-3 pr-4 py-2 border-l-4 {{ $currentRoute === 'roles.index' ? 'border-blue-500 text-blue-700 bg-blue-50' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }} text-base font-medium">Roles</a>
            <a href="{{ route('user.roles.index') }}"
                class="block pl-3 pr-4 py-2 border-l-4 {{ str_contains($currentRoute, 'user.roles.') ? 'border-blue-500 text-blue-700 bg-blue-50' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }} text-base font-medium">Users</a>
            @endif

            @if($hasLivewireRoutes)
            <a href="{{ route('permissions.livewire') }}"
                class="block pl-3 pr-4 py-2 border-l-4 {{ $currentRoute === 'permissions.livewire' ? 'border-blue-500 text-blue-700 bg-blue-50' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }} text-base font-medium">Livewire
                Permissions</a>
            <a href="{{ route('roles.matrix.livewire') }}"
                class="block pl-3 pr-4 py-2 border-l-4 {{ $currentRoute === 'roles.matrix.livewire' ? 'border-blue-500 text-blue-700 bg-blue-50' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }} text-base font-medium">Livewire
                Role Matrix</a>
            @endif
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Tailwind dropdown functionality
    const dropdownButtons = document.querySelectorAll('#permissionsDropdown, #rolesDropdown, #livewireDropdown');
    dropdownButtons.forEach(button => {
        button.addEventListener('click', () => {
            const menuId = button.id.replace('Dropdown', 'Menu');
            const menu = document.getElementById(menuId);
            if (menu) {
                menu.classList.toggle('hidden');
            }
        });
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', (e) => {
        if (!e.target.closest('[id$="Dropdown"]')) {
            document.querySelectorAll('[id$="Menu"]').forEach(menu => {
                if (!menu.classList.contains('hidden')) {
                    menu.classList.add('hidden');
                }
            });
        }
    });

    // Mobile menu toggle
    const mobileMenuButton = document.getElementById('mobileMenuButton');
    const mobileMenu = document.getElementById('mobileMenu');
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }
});
</script>
@endif