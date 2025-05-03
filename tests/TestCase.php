<?php

namespace NgarakDev\PermissionsUiWrapper\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Spatie\Permission\PermissionServiceProvider;
use NgarakDev\PermissionsUiWrapper\Providers\PermissionsUiWrapperServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            PermissionServiceProvider::class,
            PermissionsUiWrapperServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        // Setup permission config
        $app['config']->set('permission.models', [
            'permission' => \Spatie\Permission\Models\Permission::class,
            'role' => \Spatie\Permission\Models\Role::class,
        ]);

        // Set the user model
        $app['config']->set('auth.providers.users.model', \Illuminate\Foundation\Auth\User::class);

        // Set up permission UI config
        $app['config']->set('permissions-ui.permissions_manager_role', 'super-admin');
        $app['config']->set('permissions-ui.ui_framework', 'bootstrap');
    }
}
