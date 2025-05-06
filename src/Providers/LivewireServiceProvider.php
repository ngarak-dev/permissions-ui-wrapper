<?php

namespace NgarakDev\PermissionsUiWrapper\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use NgarakDev\PermissionsUiWrapper\Http\Livewire\PermissionManager;
use NgarakDev\PermissionsUiWrapper\Http\Livewire\RolePermissionMatrix;

class LivewireServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Only register components if Livewire is installed
        if (class_exists(Livewire::class)) {
            $this->registerLivewireComponents();
        }
    }

    /**
     * Register the Livewire components.
     *
     * @return void
     */
    protected function registerLivewireComponents()
    {
        // Get the configured namespace for component prefixes
        $configuredNamespace = config('permissions-ui.views.namespace', 'permission-wrapper');
        $prefix = str_replace(['-', '_'], '', $configuredNamespace);

        // Standard component registration with configured prefix
        Livewire::component($prefix . '-permission-manager', PermissionManager::class);
        Livewire::component($prefix . '-role-permission-matrix', RolePermissionMatrix::class);

        // Add backwards compatibility aliases
        Livewire::component('permission-manager', PermissionManager::class);
        Livewire::component('role-permission-matrix', RolePermissionMatrix::class);
    }
}
