# Changelog

## 0.0.2-alpha (2025-05-03)

### Changes
- Updated composer.json to remove conflict on new Laravel version

## 0.0.1-alpha (2025-05-03)

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

- **Integrations**

  - Tight integration with Spatie Laravel-Permission package
  - Laravel service provider
  - Laravel route registration
  - Blade template support

- **Developer Experience**
  - Clear documentation
  - Intuitive API
  - Flexible configuration options
  - Easily extendable
  - Minimal dependencies
