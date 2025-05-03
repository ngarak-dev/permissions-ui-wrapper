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
        Livewire::component('permission-manager', PermissionManager::class);
        Livewire::component('role-permission-matrix', RolePermissionMatrix::class);
    }
}
