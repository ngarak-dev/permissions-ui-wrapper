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
