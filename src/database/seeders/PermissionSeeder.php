<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create standard CRUD permissions for common resources
        $resources = [
            'user' => 'User Management',
            'role' => 'Role Management',
            'permission' => 'Permission Management',
            'post' => 'Post Management',
            'category' => 'Category Management',
            'comment' => 'Comment Management',
            'setting' => 'Settings Management',
        ];

        $actions = ['view', 'create', 'edit', 'delete'];

        foreach ($resources as $resource => $group) {
            foreach ($actions as $action) {
                Permission::create([
                    'name' => "{$action} {$resource}s",
                    'group' => strtolower(explode(' ', $group)[0]),
                    'guard_name' => 'web',
                ]);
            }
        }

        // Create additional system permissions
        $systemPermissions = [
            'access admin panel' => 'system',
            'manage system settings' => 'system',
            'view logs' => 'system',
            'view analytics' => 'analytics',
            'export data' => 'system',
            'import data' => 'system',
        ];

        foreach ($systemPermissions as $permission => $group) {
            Permission::create([
                'name' => $permission,
                'group' => $group,
                'guard_name' => 'web',
            ]);
        }

        // Create some random permissions if requested
        if (config('permissions-ui.seeder.create_random_permissions', false)) {
            Permission::factory()->count(10)->create();
        }
    }
}
