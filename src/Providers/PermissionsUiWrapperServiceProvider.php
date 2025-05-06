<?php

namespace NgarakDev\PermissionsUiWrapper\Providers;

use Illuminate\Support\ServiceProvider;
use NgarakDev\PermissionsUiWrapper\Console\Commands\InstallPermissionsCommand;
use NgarakDev\PermissionsUiWrapper\Console\Commands\InstallMigrationsCommand;
use NgarakDev\PermissionsUiWrapper\Console\Commands\SetSuperUserCommand;
use NgarakDev\PermissionsUiWrapper\Console\Commands\PublishSeederCommand;
use NgarakDev\PermissionsUiWrapper\Console\Commands\PublishAllCommand;
use NgarakDev\PermissionsUiWrapper\Providers\LivewireServiceProvider;

class PermissionsUiWrapperServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Publish config
        $this->publishes([
            __DIR__ . '/../config/permissions-ui.php' => config_path('permissions-ui.php'),
        ], 'permissions-ui-config');

        // Publish views - this will be handled by the install command directly
        // We're keeping this for backward compatibility and manual publishing
        $this->publishes([
            __DIR__ . '/../Resources/views' => resource_path('views/permission-wrapper'),
        ], 'permissions-ui-views');

        // Make controllers publishable
        $this->publishes([
            __DIR__ . '/../Http/Controllers' => app_path('Http/Controllers/PermissionsUiWrapper'),
        ], 'permissions-ui-controllers');

        // Make Livewire components publishable
        $this->publishes([
            __DIR__ . '/../Http/Livewire' => app_path('Http/Livewire/PermissionsUiWrapper'),
        ], 'permissions-ui-livewire');

        // Make providers publishable
        $this->publishes([
            __DIR__ . '/../Providers' => app_path('Providers/PermissionsUiWrapper'),
        ], 'permissions-ui-providers');

        // Load views from both package and application directories
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'permissions-ui');

        // Get the configured namespace
        $configuredNamespace = config('permissions-ui.views.namespace', 'permission-wrapper');

        // Load the package views with both the default namespace and the configured namespace
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', $configuredNamespace);

        // Add view namespace that will check the application's views directory first
        // This allows users to override views while keeping the package views as fallback
        $this->loadViewsFrom(resource_path("views/{$configuredNamespace}"), $configuredNamespace);

        // Route loading will be conditional based on config
        // The default is to load routes from the package
        // If the user has published routes, they can disable this in config
        if (!config('permissions-ui.disable_package_routes', false)) {
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        }

        // Load Livewire routes if Livewire is installed
        if (class_exists(\Livewire\Livewire::class)) {
            $this->loadRoutesFrom(__DIR__ . '/../routes/livewire.php');
        }

        // Register commands if running in console
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallPermissionsCommand::class,
                InstallMigrationsCommand::class,
                SetSuperUserCommand::class,
                PublishSeederCommand::class,
                PublishAllCommand::class,
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

        // Register the Livewire service provider if Livewire is installed
        if (class_exists(\Livewire\Livewire::class)) {
            $this->app->register(LivewireServiceProvider::class);
        }
    }
}
