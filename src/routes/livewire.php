<?php

use Illuminate\Support\Facades\Route;

// Get routes prefix and middleware from config
$prefix = config('permissions-ui.routes.prefix', 'permissions');
$middleware = config('permissions-ui.routes.middleware', ['web', 'auth']);

// Define the routes for Livewire components
Route::middleware($middleware)->prefix($prefix)->group(function () {
    // Livewire routes
    Route::get('/livewire/permissions', function () {
        return view('permission-wrapper::livewire.pages.permissions');
    })->name('permissions.livewire');

    Route::get('/livewire/roles-matrix', function () {
        return view('permission-wrapper::livewire.pages.roles-matrix');
    })->name('roles.matrix.livewire');
});
