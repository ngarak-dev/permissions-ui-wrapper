<?php

namespace NgarakDev\PermissionsUiWrapper\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallPermissionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions-ui:install {--force : Force overwrite existing files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install all the required resources for the Permissions UI Wrapper';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Installing Permissions UI Wrapper...');

        // First publish the Spatie Permission package migrations
        $this->publishSpatiePermissions();

        // Then publish our configuration and migrations
        $this->publishConfig();
        $this->publishMigrations();

        $this->info('Installation complete!');
        $this->info('');
        $this->info('Please run `php artisan migrate` to create the necessary database tables.');
        $this->info('To set up a super user, run: php artisan permissions-ui:super-user {userId}');

        return 0;
    }

    /**
     * Publish Spatie Permission package resources.
     */
    protected function publishSpatiePermissions()
    {
        $this->info('Publishing Spatie Permission package resources...');

        $force = $this->option('force');
        $params = ['--provider' => "Spatie\Permission\PermissionServiceProvider"];

        if ($force) {
            $params['--force'] = true;
        }

        $this->call('vendor:publish', $params);
    }

    /**
     * Publish the configuration file.
     */
    protected function publishConfig()
    {
        $this->info('Publishing configuration...');

        $force = $this->option('force');
        $params = ['--provider' => "NgarakDev\PermissionsUiWrapper\Providers\PermissionsUiWrapperServiceProvider"];

        if ($force) {
            $params['--force'] = true;
        }

        $this->call('vendor:publish', array_merge(
            $params,
            ['--tag' => 'permissions-ui-config']
        ));
    }

    /**
     * Publish the migration files.
     */
    protected function publishMigrations()
    {
        $this->info('Publishing migrations...');

        // Create migrations from stubs
        $this->installMigrations();

        $this->info('Migrations published successfully.');
    }

    /**
     * Install the migrations directly instead of publishing them.
     */
    protected function installMigrations()
    {
        // Get the migrations path
        $vendorPath = __DIR__ . '/../../database/migrations/';
        $targetPath = database_path('migrations');

        if (!is_dir($targetPath)) {
            mkdir($targetPath, 0755, true);
        }

        // Install the base tables migration
        $this->installMigrationFile(
            $vendorPath . 'create_permissions_ui_tables.php.stub',
            date('Y_m_d_His') . '_create_permissions_ui_tables.php'
        );

        // Wait 1 second to ensure different timestamps
        sleep(1);

        // Install the add group column migration
        $this->installMigrationFile(
            $vendorPath . 'add_group_to_permissions_table.php.stub',
            date('Y_m_d_His') . '_add_group_to_permissions_table.php'
        );
    }

    /**
     * Install a migration file from stub.
     */
    protected function installMigrationFile($stubPath, $filename)
    {
        $targetPath = database_path('migrations/' . $filename);

        // Skip if migration already exists
        if (File::exists($targetPath) && !$this->option('force')) {
            $this->info("Migration already exists: $filename");
            return;
        }

        // Copy the stub
        if (File::exists($stubPath)) {
            File::copy($stubPath, $targetPath);
            $this->info("Created Migration: $filename");
        } else {
            $this->error("Migration stub not found: $stubPath");
        }
    }
}
