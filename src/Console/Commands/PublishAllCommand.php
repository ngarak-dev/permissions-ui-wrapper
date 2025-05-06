<?php

namespace NgarakDev\PermissionsUiWrapper\Console\Commands;

use Illuminate\Console\Command;

class PublishAllCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions-ui:publish-all
                            {--force : Force overwrite existing files}
                            {--with-livewire : Include Livewire components}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish all components (controllers, views, Livewire components, providers)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Publishing all Permissions UI Wrapper components...');

        $force = $this->option('force') ? '--force' : '';
        $withLivewire = $this->option('with-livewire') ? '--with-livewire' : '';

        // Call the vendor:publish command with the appropriate tags
        $this->call('vendor:publish', [
            '--provider' => 'NgarakDev\PermissionsUiWrapper\Providers\PermissionsUiWrapperServiceProvider',
            '--tag' => 'permissions-ui-config',
            '--force' => $this->option('force'),
        ]);

        $this->call('vendor:publish', [
            '--provider' => 'NgarakDev\PermissionsUiWrapper\Providers\PermissionsUiWrapperServiceProvider',
            '--tag' => 'permissions-ui-views',
            '--force' => $this->option('force'),
        ]);

        $this->call('vendor:publish', [
            '--provider' => 'NgarakDev\PermissionsUiWrapper\Providers\PermissionsUiWrapperServiceProvider',
            '--tag' => 'permissions-ui-controllers',
            '--force' => $this->option('force'),
        ]);

        $this->call('vendor:publish', [
            '--provider' => 'NgarakDev\PermissionsUiWrapper\Providers\PermissionsUiWrapperServiceProvider',
            '--tag' => 'permissions-ui-providers',
            '--force' => $this->option('force'),
        ]);

        $this->call('vendor:publish', [
            '--provider' => 'NgarakDev\PermissionsUiWrapper\Providers\PermissionsUiWrapperServiceProvider',
            '--tag' => 'permissions-ui-migrations',
            '--force' => $this->option('force'),
        ]);

        if ($this->option('with-livewire')) {
            $this->call('vendor:publish', [
                '--provider' => 'NgarakDev\PermissionsUiWrapper\Providers\PermissionsUiWrapperServiceProvider',
                '--tag' => 'permissions-ui-livewire',
                '--force' => $this->option('force'),
            ]);
        }

        // Register the routes
        $this->call('permissions-ui:install', [
            '--force' => $this->option('force'),
            '--with-livewire' => $this->option('with-livewire'),
        ]);

        $this->info('All components published successfully!');

        return 0;
    }
}
