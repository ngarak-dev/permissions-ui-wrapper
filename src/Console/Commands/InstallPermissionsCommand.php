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
        $this->publishViews();
        $this->publishRoutes();
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
     * Publish the views directly to the application's views directory.
     */
    protected function publishViews()
    {
        $this->info('Publishing views to application views directory...');

        $force = $this->option('force');

        // Source views directory
        $sourcePath = __DIR__ . '/../../Resources/views';

        // Target directory in application views
        $targetPath = resource_path('views/permission-wrapper');

        // Create the target directory if it doesn't exist
        if (!File::exists($targetPath)) {
            File::makeDirectory($targetPath, 0755, true);
        }

        // Copy bootstrap views
        $this->copyDirectory($sourcePath . '/bootstrap', $targetPath . '/bootstrap', $force);

        // Copy tailwind views
        $this->copyDirectory($sourcePath . '/tailwind', $targetPath . '/tailwind', $force);

        // Copy layouts
        $this->copyDirectory($sourcePath . '/layouts', $targetPath . '/layouts', $force);

        $this->info('Views published successfully to: ' . $targetPath);
    }

    /**
     * Publish the routes to the application's routes directory.
     */
    protected function publishRoutes()
    {
        $this->info('Publishing routes to application routes directory...');

        $sourceFile = __DIR__ . '/../../routes/web.php';
        $targetFile = base_path('routes/permission-wrapper.php');

        // Check if the target file already exists
        if (File::exists($targetFile) && !$this->option('force')) {
            $this->info('Route file already exists. Use --force to overwrite.');
            return;
        }

        // Copy the routes file
        File::copy($sourceFile, $targetFile);

        // Check if we need to update the main web.php file
        $webRoutesFile = base_path('routes/web.php');
        if (File::exists($webRoutesFile)) {
            $content = File::get($webRoutesFile);

            // Only add the include if it doesn't exist
            $includeStatement = "require __DIR__.'/permission-wrapper.php';";
            if (!str_contains($content, $includeStatement)) {
                $this->info('Adding route include to main web.php file...');

                // Add a comment
                $routeInclude = "\n// Permissions UI Wrapper Routes\n{$includeStatement}\n";

                // Append to web.php
                File::append($webRoutesFile, $routeInclude);
            }
        }

        $this->info('Routes published successfully to: ' . $targetFile);
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

    /**
     * Copy a directory recursively.
     */
    protected function copyDirectory($source, $destination, $force = false)
    {
        // Create destination if it doesn't exist
        if (!File::exists($destination)) {
            File::makeDirectory($destination, 0755, true);
        }

        $files = File::files($source);
        foreach ($files as $file) {
            $targetFile = $destination . '/' . $file->getFilename();

            // Skip if file exists and not forcing
            if (File::exists($targetFile) && !$force) {
                $this->info("File already exists: " . $file->getFilename());
                continue;
            }

            File::copy($file->getPathname(), $targetFile);
            $this->info("Published: " . $file->getFilename());
        }

        // Recursive copy for subdirectories
        $directories = File::directories($source);
        foreach ($directories as $directory) {
            $directoryName = basename($directory);
            $this->copyDirectory(
                $directory,
                $destination . '/' . $directoryName,
                $force
            );
        }
    }
}
