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
- Seeders and factories for quick setup

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

### Using Seeders and Factories

The package provides seeders and factories to quickly set up roles and permissions for testing or initial production setup:

```bash
# Publish seeders and factories
php artisan permissions-ui:seeders

# Publish only seeders
php artisan permissions-ui:seeders --no-factories

# Publish only factories
php artisan permissions-ui:seeders --no-seeders
```

Once published, you can use the seeders in your application:

```bash
# Run the permission and role seeder
php artisan db:seed --class=PermissionRoleSeeder
```

Seeders include:

- Common CRUD permissions for resources like users, roles, posts, etc.
- Predefined roles (Super Admin, Admin, Editor, Author, Moderator, User)
- Proper permission assignments to each role

You can configure the seeder behavior in the `permissions-ui.php` config file under the `seeder` section:

```php
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
```

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

## Spatie Laravel-Permission Integration

This package provides a user interface for the [Spatie Laravel-Permission](https://github.com/spatie/laravel-permission) package. Here's a comprehensive guide to working with the underlying Spatie features.

### Basic Setup

Ensure your `User` model implements the `HasRoles` trait:

```php
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;

    // ...
}
```

### Working with Permissions

#### Checking Permissions

```php
// Check if a user has a specific permission
if ($user->hasPermissionTo('edit articles')) {
    // ...
}

// Check if a user has any of the permissions
if ($user->hasAnyPermission(['edit articles', 'publish articles'])) {
    // ...
}

// Check if a user has all permissions
if ($user->hasAllPermissions(['edit articles', 'publish articles'])) {
    // ...
}
```

#### Using Middleware

This package automatically registers Spatie's middleware. Use them in your routes:

```php
Route::group(['middleware' => ['permission:publish articles']], function () {
    // Routes accessible only to users with the 'publish articles' permission
});

Route::group(['middleware' => ['role:admin']], function () {
    // Routes accessible only to users with the 'admin' role
});

Route::group(['middleware' => ['role_or_permission:admin|edit articles']], function () {
    // Routes accessible to users with either 'admin' role or 'edit articles' permission
});
```

#### Blade Directives

Use Spatie's Blade directives in your views:

```blade
@role('admin')
    Admin content here
@endrole

@hasrole('writer')
    Writer content here
@endhasrole

@hasanyrole(['writer', 'editor'])
    Writer or Editor content here
@endhasanyrole

@hasallroles(['writer', 'admin'])
    Writer and Admin content here
@endhasallroles

@unlessrole('admin')
    Non-admin content here
@endunlessrole

@can('edit articles')
    <a href="{{ route('articles.edit', $article) }}">Edit</a>
@endcan

@canany(['edit articles', 'delete articles'])
    <div class="dropdown">
        <button>Actions</button>
        <div class="dropdown-menu">
            @can('edit articles')
                <a href="{{ route('articles.edit', $article) }}">Edit</a>
            @endcan
            @can('delete articles')
                <form action="{{ route('articles.destroy', $article) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Delete</button>
                </form>
            @endcan
        </div>
    </div>
@endcanany
```

### Working with Roles

#### Assigning Roles

```php
// Assign a role to a user
$user->assignRole('writer');

// Assign multiple roles
$user->assignRole(['writer', 'admin']);

// Alternative syntax
$user->assignRole('writer', 'admin');
```

#### Revoking Roles

```php
// Remove a role from a user
$user->removeRole('writer');

// Remove multiple roles
$user->removeRole(['writer', 'admin']);
```

#### Syncing Roles

```php
// Remove all roles and assign the given roles
$user->syncRoles(['writer', 'admin']);
```

#### Checking Roles

```php
// Check if a user has a role
if ($user->hasRole('writer')) {
    // ...
}

// Check if a user has any of the roles
if ($user->hasAnyRole(['writer', 'admin'])) {
    // ...
}

// Check if a user has all roles
if ($user->hasAllRoles(['writer', 'admin'])) {
    // ...
}
```

### Permission Groups

This package extends Spatie's permissions with a grouping feature. The groups are defined in the configuration:

```php
'permission_groups' => [
    'user' => [
        'label' => 'User Management',
        'description' => 'Permissions related to user management',
    ],
    // Add more groups...
],
```

You can assign a permission to a group when creating or editing it through the UI, which helps organize permissions logically.

### Super Admin Role

By default, this package looks for a role specified in the config (`permissions_manager_role`) to grant full access to the permissions management UI:

```php
'permissions_manager_role' => 'super-admin',
```

Users with this role will have full access to create, edit, and delete roles and permissions.

### Caching

Spatie Laravel-Permission uses caching to speed up permission checks. The cache is automatically reset when:

- Permissions or roles are created, updated, or deleted through the UI
- Permission/role relationships are modified

If you need to manually clear the cache:

```php
app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
```

### Database Seeding

This package provides predefined seeders that can be published and customized. To seed a production environment with initial permissions and roles:

```bash
php artisan db:seed --class=PermissionRoleSeeder
```

The seeder will create common permissions, roles, and assign them appropriately.

### Multiple Guards

Spatie Laravel-Permission supports multiple authentication guards. This UI package primarily works with the 'web' guard, but the underlying Spatie functionality supports custom guards:

```php
// Creating a role with a custom guard
$adminRole = Role::create(['name' => 'admin', 'guard_name' => 'api']);

// Assigning a role with a custom guard
$user->assignRole('admin', 'api');
```

### UUID Support

If your application uses UUIDs instead of auto-incrementing IDs, make the following changes after installing:

1. Publish the migrations with `php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="migrations"`
2. Modify the published migrations to use UUID columns
3. Update your model implementations

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
- `/permissions/roles` - View all roles
- `/permissions/roles/create` - Create a new role
- `/permissions/roles/{role}/edit` - Edit a role and assign permissions
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
