<?php

namespace NgarakDev\PermissionsUiWrapper\Providers;

use Illuminate\Support\ServiceProvider;
use NgarakDev\PermissionsUiWrapper\Console\Commands\InstallPermissionsCommand;
use NgarakDev\PermissionsUiWrapper\Console\Commands\InstallMigrationsCommand;

class PermissionsUiWrapperServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Publish config
        $this->publishes([
            __DIR__ . '/../config/permissions-ui.php' => config_path('permissions-ui.php'),
        ], 'permissions-ui-config');

        // Publish views
        $this->publishes([
            __DIR__ . '/../Resources/views' => resource_path('views/vendor/permissions-ui-wrapper'),
        ], 'permissions-ui-views');

        // Load views
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'permissions-ui');

        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        // Register commands if running in console
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallPermissionsCommand::class,
                InstallMigrationsCommand::class,
            ]);

            // Keep the original migrations publishing
            // Base permissions UI tables (should run first)
            if (! class_exists('CreatePermissionsUiTables')) {
                $this->publishes([
                    __DIR__ . '/../database/migrations/create_permissions_ui_tables.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_permissions_ui_tables.php'),
                ], 'permissions-ui-migrations');
            }

            // Add group to permissions table (should run after the tables are created)
            if (! class_exists('AddGroupToPermissionsTable')) {
                $this->publishes([
                    __DIR__ . '/../database/migrations/add_group_to_permissions_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time() + 60) . '_add_group_to_permissions_table.php'),
                ], 'permissions-ui-migrations');
            }
        }
    }

    public function register()
    {
        // Merge config
        $this->mergeConfigFrom(
            __DIR__ . '/../config/permissions-ui.php',
            'permissions-ui'
        );
    }
}
