# Changelog

## 0.0.8 (2025-05-05)

### Bug Fixes

- Fixed "Method Illuminate\Database\Eloquent\Collection::getMorphClass does not exist" error in RolePermissionMatrix component
- Updated layouts to properly include Livewire scripts and styles
- Improved permission handling in Livewire components
- Enhanced error handling for authorization checks

## 0.0.7 (2025-05-03)

### Changes

- Added Livewire components for reactive permission management
- Added PermissionManager component for CRUD operations on permissions
- Added RolePermissionMatrix component for interactive permission assignment to roles
- Enhanced installation command with Livewire support options:
  - `--with-livewire`: Install both standard and Livewire components
  - `--with-livewire-only`: Install only Livewire components
- Added dedicated Livewire routes under `/permissions/livewire/`
- Added documentation for Livewire components and installation options
- Fixed error handling in the permission manager component

## 0.0.6-alpha (2025-05-03)

### Changes

- Added factories and seeders for roles and permissions
- Command to publish seeders and factories to application
- Configuration options for seeder behavior
- Comprehensive documentation on Spatie Laravel-Permission integration
- Added examples for Blade directives, middleware, and permission/role checks
- Documentation for advanced features (UUID, multiple guards, caching)

## 0.0.5-alpha (2025-05-03)

### Changes

- Improved view publishing to application directory
- Custom route publishing for better integration
- Support for view customization without editing vendor files

## 0.0.4-alpha (2025-05-03)

### Changes

- Automatic installation of Spatie Permission migrations
- Super user setup command `permissions-ui:super-user`

## 0.0.3-alpha (2025-05-03)

### Changes

- Updated spatie/laravel-permission library

## 0.0.2-alpha (2025-05-03)

### Changes

- Updated composer.json to remove conflict on new Laravel version

## 0.0.1-alpha (2025-05-03)

- First Alpha Release

### Features

- **Dual UI Framework Support**

  - Complete Bootstrap implementation with responsive design
  - Complete Tailwind CSS implementation with responsive design
  - UI framework selection via configuration

- **Permission Management**

  - CRUD operations for permissions
  - Permission listing with search and filter
  - Permission grouping system
  - Group-based permission organization
  - Pagination support for large permission sets

- **Role Management**

  - CRUD operations for roles
  - Role listing with search and filter
  - Permission assignment to roles
  - Bulk permission updates
  - Pagination support for large role sets

- **User Management**

  - Role assignment interface for users
  - User role overview
  - Bulk role assignment capabilities
  - Searchable user listing
  - Super user command for quick admin setup
  - Option to create new user with admin privileges

- **Authorization**

  - Configurable admin roles via 'permissions_manager_role'
  - Permission-based access controls
  - Graceful handling of different Laravel user models
  - Support for Spatie Permission methods (hasRole, can)

- **Installation & Configuration**

  - Custom artisan command `permissions-ui:install`
  - Dedicated migrations command `permissions-ui:migrations`
  - Automatic config publishing
  - Composer script integration
  - Migration sequencing support
  - Force option for overwriting existing files
  - Automatic installation of Spatie Permission migrations
  - Super user setup command `permissions-ui:super-user`
  - Views published to application directory for easy customization
  - Routes published to application for easy customization

- **Integrations**

  - Tight integration with Spatie Laravel-Permission package
  - Laravel service provider
  - Laravel route registration
  - Blade template support
  - Automatic Spatie migrations publishing
  - Integration with application's web.php routes

- **Developer Experience**
  - Clear documentation
  - Intuitive API
  - Flexible configuration options
  - Easily extendable
  - Minimal dependencies
  - Customizable without editing vendor files
