<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Spatie\Permission\Models\Role;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->jobTitle(),
            'guard_name' => 'web',
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure()
    {
        return $this->afterCreating(function (Role $role) {
            // 30% chance to have random permissions assigned
            if ($this->faker->boolean(30)) {
                $permissionCount = $this->faker->numberBetween(1, 5);
                $permissionIds = \Spatie\Permission\Models\Permission::inRandomOrder()
                    ->limit($permissionCount)
                    ->pluck('id');

                $role->permissions()->attach($permissionIds);
            }
        });
    }

    /**
     * Indicate that the role is an admin role.
     */
    public function admin()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'Admin',
            ];
        });
    }

    /**
     * Indicate that the role is a super admin role.
     */
    public function superAdmin()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'Super Admin',
            ];
        })->afterCreating(function (Role $role) {
            // Super Admin gets all permissions
            $allPermissions = \Spatie\Permission\Models\Permission::all();
            $role->syncPermissions($allPermissions);
        });
    }

    /**
     * Creates a role with no permissions.
     */
    public function withoutPermissions()
    {
        return $this->afterCreating(function (Role $role) {
            $role->permissions()->detach();
        });
    }

    /**
     * Creates a role with specific permissions.
     */
    public function withPermissions(array $permissions)
    {
        return $this->afterCreating(function (Role $role) use ($permissions) {
            $role->syncPermissions($permissions);
        });
    }
}
