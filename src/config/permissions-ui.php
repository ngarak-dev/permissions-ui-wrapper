<?php

return [
    /*
    |--------------------------------------------------------------------------
    | UI Framework Selection
    |--------------------------------------------------------------------------
    |
    | This value is used to determine which UI framework to use.
    | Supported: "bootstrap", "tailwind"
    |
    */
    'ui_framework' => 'bootstrap',

    /*
    |--------------------------------------------------------------------------
    | Routes Configuration
    |--------------------------------------------------------------------------
    |
    | Control the route prefix and middleware
    |
    */
    'routes' => [
        'prefix' => 'permissions',
        'middleware' => ['web', 'auth'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Route Loading
    |--------------------------------------------------------------------------
    |
    | If you have published the routes to your application, you can disable
    | the package from loading its internal routes. Set this to true if you're
    | using the published routes in permission-wrapper.php
    |
    */
    'disable_package_routes' => false,

    /*
    |--------------------------------------------------------------------------
    | View Settings
    |--------------------------------------------------------------------------
    |
    | The package can look for views in both the package and your application
    | directory. Settings related to view loading.
    |
    */
    'views' => [
        'namespace' => 'permission-wrapper', // The namespace to use for published views
    ],

    /*
    |--------------------------------------------------------------------------
    | Seeder Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the permissions and roles seeder.
    |
    */
    'seeder' => [
        // Whether to clear existing permissions and roles before seeding
        'clear_before_seeding' => false,

        // Whether to create random permissions in addition to the defined ones
        'create_random_permissions' => false,

        // Whether to create random roles in addition to the defined ones
        'create_random_roles' => false,

        // If set, this user ID will automatically be assigned the Super Admin role
        'super_admin_user_id' => null,
    ],

    /*
    |--------------------------------------------------------------------------
    | Permission Management
    |--------------------------------------------------------------------------
    |
    | Control which roles/permissions can manage other roles/permissions
    |
    */
    'permissions_manager_role' => 'super-admin',

    /*
    |--------------------------------------------------------------------------
    | Permission Groups
    |--------------------------------------------------------------------------
    |
    | Define permission groups for better organization
    |
    */
    'permission_groups' => [
        'user' => [
            'label' => 'User Management',
            'description' => 'Permissions related to user management',
        ],
        'content' => [
            'label' => 'Content Management',
            'description' => 'Permissions related to content management',
        ],
        'settings' => [
            'label' => 'Settings',
            'description' => 'Permissions related to system settings',
        ],
        // Add more groups as needed
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Messages
    |--------------------------------------------------------------------------
    |
    | Custom validation messages for permissions and roles
    |
    */
    'validation_messages' => [
        'name' => [
            'required' => 'The name field is required.',
            'unique' => 'This name is already in use. Please choose a different name.',
        ],
        'roles' => [
            'exists' => 'One or more selected roles do not exist.',
        ],
        'permissions' => [
            'exists' => 'One or more selected permissions do not exist.',
        ],
    ],
];
