<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Clear existing roles and permissions if specified in config
        if (config('permissions-ui.seeder.clear_before_seeding', false)) {
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            // Use truncate instead of delete to reset IDs
            DB::table('role_has_permissions')->truncate();
            DB::table('model_has_roles')->truncate();
            DB::table('model_has_permissions')->truncate();
            DB::table('roles')->truncate();
            DB::table('permissions')->truncate();

            $this->command->info('Truncated all permission and role tables');
        }

        // Run Permission seeder first
        $this->call(PermissionSeeder::class);
        $this->command->info('Permissions seeded successfully');

        // Then run Role seeder which assigns permissions
        $this->call(RoleSeeder::class);
        $this->command->info('Roles seeded successfully');

        // Automatically assign Super Admin role to a user if configured
        if ($superAdminId = config('permissions-ui.seeder.super_admin_user_id')) {
            $userModel = config('auth.providers.users.model');
            $user = $userModel::find($superAdminId);

            if ($user && method_exists($user, 'assignRole')) {
                $user->assignRole('Super Admin');
                $this->command->info("Super Admin role assigned to user ID: {$superAdminId}");
            } else {
                $this->command->warn("Could not assign Super Admin role to user ID: {$superAdminId}");
            }
        }
    }
}
