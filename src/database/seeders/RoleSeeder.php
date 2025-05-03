<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create common roles
        $rolesWithPermissions = [
            'Super Admin' => ['*'], // All permissions
            'Admin' => [
                'access admin panel',
                'view users',
                'create users',
                'edit users',
                'delete users',
                'view roles',
                'create roles',
                'edit roles',
                'delete roles',
                'view permissions',
                'edit permissions',
                'view posts',
                'create posts',
                'edit posts',
                'delete posts',
                'view categories',
                'create categories',
                'edit categories',
                'delete categories',
                'view comments',
                'create comments',
                'edit comments',
                'delete comments',
                'view settings',
                'edit settings',
                'view analytics',
            ],
            'Editor' => [
                'access admin panel',
                'view posts',
                'create posts',
                'edit posts',
                'delete posts',
                'view categories',
                'create categories',
                'edit categories',
                'view comments',
                'create comments',
                'edit comments',
                'delete comments',
            ],
            'Author' => [
                'access admin panel',
                'view posts',
                'create posts',
                'edit posts',
                'view categories',
                'view comments',
                'create comments',
                'edit comments',
            ],
            'Moderator' => [
                'access admin panel',
                'view comments',
                'edit comments',
                'delete comments',
                'view posts',
            ],
            'User' => [
                'view posts',
                'view categories',
                'view comments',
                'create comments',
            ],
        ];

        foreach ($rolesWithPermissions as $roleName => $permissions) {
            $role = Role::create(['name' => $roleName, 'guard_name' => 'web']);

            // Special case for Super Admin - give all permissions
            if (in_array('*', $permissions)) {
                $allPermissions = Permission::all();
                $role->syncPermissions($allPermissions);
            } else {
                $role->syncPermissions($permissions);
            }
        }

        // Create some random roles if requested
        if (config('permissions-ui.seeder.create_random_roles', false)) {
            Role::factory()->count(3)->create();
        }
    }
}
