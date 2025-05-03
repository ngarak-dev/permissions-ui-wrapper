<?php

use Illuminate\Support\Facades\Route;
use NgarakDev\PermissionsUiWrapper\Http\Controllers\PermissionController;
use NgarakDev\PermissionsUiWrapper\Http\Controllers\UserRoleController;

Route::group([
    'prefix' => config('permissions-ui.routes.prefix', 'permissions'),
    'middleware' => config('permissions-ui.routes.middleware', ['web', 'auth']),
], function () {
    Route::get('/', [PermissionController::class, 'index'])->name('permissions.index');
    Route::get('/create', [PermissionController::class, 'create'])->name('permissions.create');
    Route::post('/', [PermissionController::class, 'store'])->name('permissions.store');
    Route::get('/{permission}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
    Route::put('/{permission}', [PermissionController::class, 'update'])->name('permissions.update');
    Route::delete('/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');

    // Role routes
    Route::get('/roles', [PermissionController::class, 'indexRoles'])->name('roles.index');
    Route::get('/roles/create', [PermissionController::class, 'createRole'])->name('roles.create');
    Route::post('/roles', [PermissionController::class, 'storeRole'])->name('roles.store');
    Route::get('/roles/{role}/edit', [PermissionController::class, 'editRole'])->name('roles.edit');
    Route::put('/roles/{role}', [PermissionController::class, 'updateRole'])->name('roles.update');
    Route::delete('/roles/{role}', [PermissionController::class, 'destroyRole'])->name('roles.destroy');

    // User role management routes
    Route::get('/users', [UserRoleController::class, 'index'])->name('user.roles.index');
    Route::get('/users/{user}/edit', [UserRoleController::class, 'edit'])->name('user.roles.edit');
    Route::put('/users/{user}', [UserRoleController::class, 'update'])->name('user.roles.update');
});
