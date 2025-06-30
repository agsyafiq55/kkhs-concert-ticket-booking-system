# KKHS Concert Ticket Booking System

A Laravel-based ticket booking system for concerts with role-based access control.

## Role Hierarchy System

The system implements a four-tier role hierarchy using Laravel Spatie Permissions:

### 1. Super Admin
- **Access**: All permissions in the system
- **Special Features**: 
  - User role and permission management
  - Access to role hierarchy configuration
  - Can assign/modify all user roles
- **Permissions**: All available permissions
- **Routes**: All admin routes + role management

### 2. Admin
- **Access**: All system features except role management
- **Features**:
  - Concert management (create, edit, delete)
  - Ticket management (create, edit, delete)
  - User management (create, edit, delete users)
  - Sales reports and analytics
  - Walk-in ticket management
- **Permissions**: All permissions except `manage roles`, `manage permissions`, `assign roles`
- **Routes**: All admin routes except role management

### 3. Teacher
- **Access**: Limited to ticket operations
- **Features**:
  - Scan tickets for entry validation
  - Assign tickets to students
  - Scan walk-in sales
- **Permissions**: `scan tickets`, `assign tickets`, `scan walk-in sales`, `view concerts`, `view tickets`
- **Routes**: Teacher-specific routes only

### 4. Student
- **Access**: View own tickets only
- **Features**:
  - View personal ticket purchases
  - Download/print tickets
  - View QR codes for entry
- **Permissions**: `view own tickets`
- **Routes**: Student-specific routes only

## Permission Structure

### Concert Permissions
- `create concerts`
- `view concerts` 
- `edit concerts`
- `delete concerts`

### Ticket Permissions
- `create tickets`
- `view tickets`
- `edit tickets` 
- `delete tickets`
- `confirm tickets`
- `scan tickets`
- `assign tickets`

### User Management Permissions
- `create users`
- `view users`
- `edit users`
- `delete users`

### Role Management Permissions (Super Admin Only)
- `manage roles`
- `manage permissions`
- `assign roles`

### Reporting & Sales Permissions
- `view ticket sales`
- `generate reports`

### Walk-in Management Permissions
- `manage walk-in tickets`
- `scan walk-in sales`

### Student Permissions
- `view own tickets`

## Commands

### Assign Roles to Users

```bash
# Assign admin role
php artisan app:assign-admin-role user@example.com

# Assign super-admin role
php artisan app:assign-admin-role user@example.com --super
```

### Seed Database with Roles and Permissions

```bash
# Initial setup (for new installations)
php artisan db:seed --class=RoleSeeder
php artisan db:seed --class=PermissionSeeder

# Update existing installations
php artisan db:seed --class=UpdateRolesAndPermissionsSeeder
```

## Route Protection

Routes are protected using middleware:

- `role:super-admin` - Super admin only
- `role:admin|super-admin` - Admin and super admin
- `role:teacher` - Teachers only  
- `role:student` - Students only
- `permission:manage roles` - Permission-based access

## Implementation Files

### Models
- `app/Models/User.php` - Extended with role checking methods

### Seeders
- `database/seeders/RoleSeeder.php` - Creates roles
- `database/seeders/PermissionSeeder.php` - Creates permissions and assigns them
- `database/seeders/UpdateRolesAndPermissionsSeeder.php` - Updates existing installations

### Livewire Components
- `app/Livewire/Admin/UserManagement.php` - User management (super admin only)
- `app/Livewire/Admin/RolePermissionManagement.php` - Role/permission management (super admin only)
- `app/Livewire/Teacher/ScanTickets.php` - Ticket scanning (teachers only)
- `app/Livewire/Student/MyTickets.php` - Student ticket view (students only)

### Views
- `resources/views/livewire/admin/role-permission-management.blade.php` - Role management interface
- `resources/views/components/layouts/app/sidebar.blade.php` - Role-based navigation

### Routes
- `routes/web.php` - Protected with role and permission middleware

## Security Features

1. **Permission-based access control** - All components check permissions before execution
2. **Route protection** - Middleware ensures only authorized users access routes  
3. **Role hierarchy enforcement** - Lower roles cannot access higher-level features
4. **Permission caching** - Uses Spatie's built-in caching for performance

## Getting Started

1. Run migrations and seeders
2. Create a super admin user: `php artisan app:assign-admin-role admin@example.com --super`
3. Login as super admin to manage other users and roles
4. Assign appropriate roles to users through the admin interface 