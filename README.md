# Permissions UI Wrapper

A UI wrapper for Spatie Laravel-Permission with both Bootstrap and Tailwind CSS support.

## Features

- Complete permission and role management UI
- Support for both Bootstrap and Tailwind CSS
- User role assignment interface
- Permission grouping for better organization
- Pagination, sorting, and filtering
- Comprehensive authorization controls
- Automatic migrations and config publishing
- Custom artisan commands for easy installation
- Customizable views and routes

## Installation

You can install the package via composer:

```bash
composer require ngarak-dev/permissions-ui-wrapper
```

The package automatically:

- Publishes the configuration file to `config/permissions-ui.php`
- Runs `composer dump-autoload` after installation or update
- Installs migrations when running `php artisan migrate`

### Manual Installation

If you prefer manual installation, you can use the provided artisan commands:

```bash
# Install everything (config & migrations)
php artisan permissions-ui:install

# Install only migrations
php artisan permissions-ui:migrations
```

These commands will automatically publish Spatie Permission package migrations before installing the package's own migrations, ensuring proper setup sequence.

You can use the `--force` flag with either command to overwrite existing files.

### Setting up Super User

After installation, you should set up a super user who can manage permissions and roles:

```bash
# Set an existing user as super user
php artisan permissions-ui:super-user {userId}

# Create a new user as super user
php artisan permissions-ui:super-user --create
```

The new command allows:

- Setting an existing user as a super user with full permissions management access
- Creating a new user and assigning the super user role
- Automatically creating the super admin role if it doesn't exist
- Assigning all permissions to the role (optional)

## Configuration

If you want to manually publish the configuration:

```bash
php artisan vendor:publish --tag="permissions-ui-config"
```

### Views and Routes

The `permissions-ui:install` command automatically:

1. Copies all view files to `resources/views/permission-wrapper/` in your application
2. Publishes routes to `routes/permission-wrapper.php`
3. Adds an include statement to your application's `routes/web.php` file

This approach allows you to customize views and routes directly in your application without modifying the package files.

If you prefer to manually publish the views:

```bash
php artisan vendor:publish --tag="permissions-ui-views"
```

### Customizing Views

All published views are in the `resources/views/permission-wrapper` directory, organized by UI framework (bootstrap/tailwind) and feature type. You can modify these views directly without affecting the package.

### Customizing Routes

After installation, you'll find a `permission-wrapper.php` file in your routes directory. You can modify these routes as needed. To prevent the package from loading its internal routes, set the following in your config:

```php
// config/permissions-ui.php
'disable_package_routes' => true,
```

### Running Migrations

Run migrations to add permission groups:

```bash
php artisan migrate
```

## Usage

### Select UI Framework

Choose between Bootstrap and Tailwind UI in the config:

```php
// config/permissions-ui.php
return [
    'ui_framework' => 'bootstrap', // or 'tailwind'
];
```

### Permission Groups

Organize permissions into logical groups:

```php
'permission_groups' => [
    'user' => [
        'label' => 'User Management',
        'description' => 'Permissions related to user management',
    ],
    // Add more groups...
],
```

### Routes

The package registers the following routes by default:

- `/permissions` - View all permissions
- `/permissions/create` - Create a new permission
- `/permissions/{permission}/edit` - Edit a permission
- `/roles` - View all roles
- `/roles/create` - Create a new role
- `/roles/{role}/edit` - Edit a role and assign permissions
- `/permissions/users` - Manage user roles
- `/permissions/users/{user}/edit` - Edit roles for a specific user

### Authorization

By default, only users with the role specified in `permissions_manager_role` config (default: 'super-admin') can access these routes. You can customize this in the configuration file.

## Testing

```bash
composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
