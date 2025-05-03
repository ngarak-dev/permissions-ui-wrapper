<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Spatie\Permission\Models\Permission;

class PermissionFactory extends Factory
{
    protected $model = Permission::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->word(),
            'guard_name' => 'web',
            'group' => $this->faker->randomElement(['user', 'content', 'settings', 'analytics', 'system']),
        ];
    }

    /**
     * Indicate that the permission belongs to the user group.
     */
    public function userGroup()
    {
        return $this->state(function (array $attributes) {
            return [
                'group' => 'user',
            ];
        });
    }

    /**
     * Indicate that the permission belongs to the content group.
     */
    public function contentGroup()
    {
        return $this->state(function (array $attributes) {
            return [
                'group' => 'content',
            ];
        });
    }

    /**
     * Indicate that the permission belongs to the settings group.
     */
    public function settingsGroup()
    {
        return $this->state(function (array $attributes) {
            return [
                'group' => 'settings',
            ];
        });
    }
}
