<?php

namespace NgarakDev\PermissionsUiWrapper\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PublishSeederCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions-ui:seeders 
                            {--force : Force overwrite existing files}
                            {--no-factories : Do not publish factories}
                            {--no-seeders : Do not publish seeders}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish permission and role seeders and factories';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Publishing permission and role seeders and factories...');

        if (!$this->option('no-factories')) {
            $this->publishFactories();
        }

        if (!$this->option('no-seeders')) {
            $this->publishSeeders();
        }

        $this->info('');
        $this->info('To use the seeders, add the following to your DatabaseSeeder:');
        $this->info('$this->call(\Database\Seeders\PermissionRoleSeeder::class);');
        $this->info('');
        $this->info('Or run directly:');
        $this->info('php artisan db:seed --class=PermissionRoleSeeder');

        return 0;
    }

    /**
     * Publish the factory files.
     */
    protected function publishFactories()
    {
        $this->info('Publishing factory files...');

        $sourcePath = __DIR__ . '/../../database/factories';
        $targetPath = database_path('factories');

        // Create the target directory if it doesn't exist
        if (!File::exists($targetPath)) {
            File::makeDirectory($targetPath, 0755, true);
        }

        // Copy each factory file
        $files = [
            'PermissionFactory.php',
            'RoleFactory.php',
        ];

        foreach ($files as $file) {
            $source = $sourcePath . '/' . $file;
            $target = $targetPath . '/' . $file;

            if (File::exists($target) && !$this->option('force')) {
                $this->info("Factory file already exists: {$file} (use --force to overwrite)");
                continue;
            }

            if (File::exists($source)) {
                File::copy($source, $target);
                $this->info("Published factory: {$file}");
            } else {
                $this->error("Source factory file not found: {$file}");
            }
        }
    }

    /**
     * Publish the seeder files.
     */
    protected function publishSeeders()
    {
        $this->info('Publishing seeder files...');

        $sourcePath = __DIR__ . '/../../database/seeders';
        $targetPath = database_path('seeders');

        // Create the target directory if it doesn't exist
        if (!File::exists($targetPath)) {
            File::makeDirectory($targetPath, 0755, true);
        }

        // Copy each seeder file
        $files = [
            'PermissionSeeder.php',
            'RoleSeeder.php',
            'PermissionRoleSeeder.php',
        ];

        foreach ($files as $file) {
            $source = $sourcePath . '/' . $file;
            $target = $targetPath . '/' . $file;

            if (File::exists($target) && !$this->option('force')) {
                $this->info("Seeder file already exists: {$file} (use --force to overwrite)");
                continue;
            }

            if (File::exists($source)) {
                File::copy($source, $target);
                $this->info("Published seeder: {$file}");
            } else {
                $this->error("Source seeder file not found: {$file}");
            }
        }
    }
}
