<?php

namespace NgarakDev\PermissionsUiWrapper\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallMigrationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions-ui:migrations {--force : Force overwrite existing migrations}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the Permissions UI Wrapper migrations';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Installing Permissions UI Wrapper migrations...');

        // First publish the Spatie Permission package migrations
        $this->publishSpatiePermissions();

        // Get the migrations path
        $vendorPath = __DIR__ . '/../../database/migrations/';
        $targetPath = database_path('migrations');

        if (!is_dir($targetPath)) {
            mkdir($targetPath, 0755, true);
        }

        // Install the base tables migration
        $timestamp = date('Y_m_d_His');
        $this->installMigrationFile(
            $vendorPath . 'create_permissions_ui_tables.php.stub',
            $timestamp . '_create_permissions_ui_tables.php'
        );

        // Wait 1 second to ensure different timestamps
        sleep(1);

        // Install the add group column migration
        $timestamp = date('Y_m_d_His');
        $this->installMigrationFile(
            $vendorPath . 'add_group_to_permissions_table.php.stub',
            $timestamp . '_add_group_to_permissions_table.php'
        );

        $this->info('Migrations installed successfully!');
        $this->info('');
        $this->info('Run `php artisan migrate` to run the migrations.');

        return 0;
    }

    /**
     * Publish Spatie Permission package resources.
     */
    protected function publishSpatiePermissions()
    {
        $this->info('Publishing Spatie Permission package migrations...');

        $force = $this->option('force');
        $params = ['--provider' => "Spatie\Permission\PermissionServiceProvider"];

        if ($force) {
            $params['--force'] = true;
        }

        $this->call('vendor:publish', $params);
    }

    /**
     * Install a migration file from stub.
     */
    protected function installMigrationFile($stubPath, $filename)
    {
        $targetPath = database_path('migrations/' . $filename);

        // Skip if migration already exists (unless force option is used)
        if (File::exists($targetPath) && !$this->option('force')) {
            $this->warn("Migration file already exists: $filename");
            $this->warn("Use --force to overwrite");
            return;
        }

        // Copy the stub to the migrations directory
        if (File::exists($stubPath)) {
            File::copy($stubPath, $targetPath);
            $this->info("Created Migration: $filename");
        } else {
            $this->error("Migration stub not found: $stubPath");
        }
    }
}
