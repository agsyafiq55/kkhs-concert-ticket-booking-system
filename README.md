# KKHS Concert Ticket Booking System

A comprehensive Laravel-based ticket booking system for concert events featuring role-based access control, QR code validation, walk-in ticket support, and real-time sales analytics.

## 🎯 System Overview

The KKHS Concert Ticket Booking System is designed for educational institutions to manage concert ticket sales efficiently. It supports both student ticket assignments and walk-in sales for external customers, with a sophisticated role-based permission system ensuring secure operations.

### ✨ Key Features

- **🔐 Four-tier role hierarchy** with granular permissions
- **🎫 Comprehensive ticket management** with real-time availability tracking
- **📱 QR code-based ticket validation** for secure entry control
- **🚶 Walk-in ticket support** for non-student customers
- **📊 Real-time sales analytics** with export capabilities
- **👥 Bulk student account management** via Excel/CSV import
- **📧 Automated email notifications** with printable tickets
- **🛡️ Advanced security features** with permission-based access control

## 🏗️ Role Hierarchy System

The system implements a four-tier role hierarchy using Laravel Spatie Permissions:

### 1. 👑 Super Admin
- **Access**: Complete system control and oversight
- **Special Features**: 
  - User role and permission management
  - System configuration access
  - Can assign/modify all user roles
  - Access to all system features
- **Permissions**: All available permissions in the system
- **Use Case**: System administrators and IT staff

### 2. 🛠️ Admin
- **Access**: All operational features except role management
- **Features**:
  - Concert management (create, edit, delete)
  - Ticket management (create, edit, delete)
  - User management (create, edit, delete users)
  - Sales reports and analytics with export options
  - Walk-in ticket management and pre-generation
  - Bulk student account uploads
- **Permissions**: All permissions except `manage roles`, `manage permissions`, `assign roles`
- **Use Case**: Event managers, administrative staff

### 3. 👨‍🏫 Teacher
- **Access**: Limited to ticket operations and scanning
- **Features**:
  - Assign tickets to students with cart functionality
  - Scan tickets for entry validation during events
  - Process walk-in ticket sales and payment collection
  - View concert and ticket information
- **Permissions**: `scan tickets`, `assign tickets`, `scan walk-in sales`, `view concerts`, `view tickets`
- **Use Case**: Faculty members, event staff

### 4. 🎓 Student
- **Access**: Personal ticket management only
- **Features**:
  - View personal ticket purchases and history
  - Download and print tickets with QR codes
  - Access ticket details and event information
- **Permissions**: `view own tickets`
- **Use Case**: Students and registered users

## 📋 Permission Structure

### Concert Management Permissions
- `create concerts` - Create new concert events
- `view concerts` - View concert listings and details
- `edit concerts` - Modify existing concert information
- `delete concerts` - Remove concerts from the system

### Ticket Management Permissions
- `create tickets` - Create new ticket types for concerts
- `view tickets` - View ticket information and availability
- `edit tickets` - Modify ticket details and pricing
- `delete tickets` - Remove ticket types
- `confirm tickets` - Validate ticket purchases
- `scan tickets` - Scan QR codes for entry validation
- `assign tickets` - Assign tickets to students

### User Management Permissions
- `create users` - Create new user accounts
- `view users` - View user listings and profiles
- `edit users` - Modify user information
- `delete users` - Remove user accounts

### Role Management Permissions (Super Admin Only)
- `manage roles` - Create and modify user roles
- `manage permissions` - Assign permissions to roles
- `assign roles` - Assign roles to users

### Reporting & Analytics Permissions
- `view ticket sales` - Access sales reports and analytics
- `generate reports` - Create and export detailed reports

### Walk-in Management Permissions
- `manage walk-in tickets` - Create and manage walk-in tickets
- `scan walk-in sales` - Process walk-in ticket payments

### Student-Specific Permissions
- `view own tickets` - View personal ticket purchases

### Special Permissions
- `bulk upload students` - Import multiple student accounts

## 🚀 Getting Started

### Prerequisites
- PHP 8.1 or higher
- Composer
- MySQL/PostgreSQL database
- Node.js and NPM (for asset compilation)

### Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd kkhs-concert-ticket-booking-system
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure database and mail settings in `.env`**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=kkhs_concert_system
   DB_USERNAME=your_username
   DB_PASSWORD=your_password

   MAIL_MAILER=smtp
   MAIL_HOST=your_smtp_host
   MAIL_PORT=587
   MAIL_USERNAME=your_email
   MAIL_PASSWORD=your_password
   ```

5. **Database setup**
   ```bash
   php artisan migrate
   php artisan db:seed --class=RoleSeeder
   php artisan db:seed --class=PermissionSeeder
   ```

6. **Compile assets**
   ```bash
   npm run build
   ```

7. **Create a super admin user**
   ```bash
   php artisan app:assign-admin-role admin@example.com --super
   ```

8. **Start the development server**
   ```bash
   php artisan serve
   ```

## 🎛️ Administrative Commands

### Role Assignment Commands

```bash
# Assign admin role to a user
php artisan app:assign-admin-role user@example.com

# Assign super-admin role to a user
php artisan app:assign-admin-role user@example.com --super
```

### Database Seeding Commands

```bash
# Initial setup (for new installations)
php artisan db:seed --class=RoleSeeder
php artisan db:seed --class=PermissionSeeder

# Update existing installations with new roles/permissions
php artisan db:seed --class=UpdateRolesAndPermissionsSeeder

# Create sample admin user
php artisan db:seed --class=AdminUserSeeder
```

### Maintenance Commands

```bash
# Clear application cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Check super admin permissions
php artisan app:check-super-admin-permissions

# Fix super admin permissions if needed
php artisan app:fix-super-admin-permissions
```

## 🎯 Core Functionality

### Concert Management
- **Create Events**: Set up concerts with detailed information (title, description, venue, date, times)
- **Event Scheduling**: Manage multiple concerts with different dates and venues
- **Capacity Planning**: Set overall event capacity and manage ticket allocation

### Ticket System
- **Multiple Ticket Types**: Create different ticket categories (VIP, General, Student, etc.)
- **Dynamic Pricing**: Set individual prices for each ticket type
- **Inventory Management**: Real-time tracking of available vs. sold tickets
- **QR Code Generation**: Unique QR codes for each ticket purchase

### Sales Management
- **Student Assignments**: Teachers can assign tickets to students with payment confirmation
- **Walk-in Sales**: Support for on-site ticket sales to non-students
- **Cart Functionality**: Multi-ticket purchases with real-time total calculation
- **Payment Tracking**: Mark tickets as paid and generate confirmations

### Entry Control
- **QR Code Scanning**: Mobile-friendly interface for ticket validation
- **Real-time Validation**: Instant verification of ticket authenticity and status
- **Duplicate Prevention**: System prevents multiple uses of the same ticket
- **Status Tracking**: Track ticket usage and entry times

### Reporting & Analytics
- **Sales Reports**: Comprehensive sales data with filtering options
- **Revenue Tracking**: Real-time revenue calculations and summaries
- **Export Options**: CSV and PDF export for external analysis
- **Teacher Performance**: Track sales by individual teachers

## 🔒 Security Features

1. **Permission-based Access Control**: Every feature checks user permissions before execution
2. **Route Protection**: Middleware ensures only authorized users access specific routes
3. **Role Hierarchy Enforcement**: Lower-level roles cannot access higher-level features
4. **QR Code Security**: Cryptographically secure QR codes prevent duplication
5. **Session Management**: Secure user sessions with automatic logout
6. **Data Validation**: Comprehensive input validation and sanitization
7. **SQL Injection Prevention**: Eloquent ORM protection against database attacks

## 📁 Implementation Files

### Models
- `app/Models/User.php` - User model with role checking methods
- `app/Models/Concert.php` - Concert events and scheduling
- `app/Models/Ticket.php` - Ticket types and pricing
- `app/Models/TicketPurchase.php` - Individual ticket sales and assignments

### Livewire Components
- `app/Livewire/Admin/` - Administrative interface components
  - `Concerts/` - Concert management (Index, Create, Edit)
  - `Tickets/` - Ticket management (Index, Create, Edit)
  - `UserManagement.php` - User account management
  - `RolePermissionManagement.php` - Role and permission management
  - `TicketSales.php` - Sales reporting and analytics
  - `WalkInTickets.php` - Walk-in ticket management
  - `BulkStudentUpload.php` - Bulk student account import
- `app/Livewire/Teacher/` - Teacher interface components
  - `AssignTickets.php` - Student ticket assignment with cart
  - `ScanTickets.php` - Entry validation scanner
  - `ScanWalkInSales.php` - Walk-in payment processing
- `app/Livewire/Student/` - Student interface components
  - `MyTickets.php` - Personal ticket management

### Database Structure
- `database/migrations/` - Database schema definitions
- `database/seeders/` - Role, permission, and sample data seeders
- `database/factories/` - Model factories for testing

### Views and Components
- `resources/views/livewire/` - Livewire component templates
- `resources/views/components/` - Reusable UI components
- `resources/views/mail/` - Email notification templates

## 🎨 UI Framework

The system uses **Flux UI** components with a modern, responsive design:

- **Color Scheme**: Rose accent colors with stone/zinc base colors
- **Typography**: Instrument Sans font family
- **Dark Mode**: Full dark mode support
- **Mobile Responsive**: Optimized for all device sizes
- **Accessibility**: WCAG compliant components

## 🔧 Configuration

### Route Protection

Routes are protected using middleware combinations:

- `role:super-admin` - Super admin exclusive access
- `role:admin|super-admin` - Admin level access
- `role:teacher` - Teacher exclusive access  
- `role:student` - Student exclusive access
- `permission:specific-permission` - Granular permission-based access

## 📊 Usage Statistics

Track system usage with built-in analytics:

- **Active Users**: Monitor user engagement
- **Ticket Sales**: Real-time sales tracking
- **Revenue Reports**: Financial performance metrics
- **Event Attendance**: Entry validation statistics

## 🐛 Troubleshooting

### Common Issues

**Permission Denied Errors**
- Verify user has correct role assignment
- Check permission seeder has been run
- Confirm user is logged in with appropriate account

**QR Code Scanning Issues**
- Ensure camera permissions are granted
- Check QR code format and readability
- Use manual code entry as fallback

**Email Notifications Not Sending**
- Verify SMTP configuration in `.env`
- Check mail queue processing
- Confirm recipient email addresses are valid

**Walk-in Tickets Invalid**
- Ensure walk-in tickets are marked as "sold"
- Use Walk-in Sales Scanner to collect payment
- Verify ticket status in admin panel

**Bulk Upload Failures**
- Download and use provided Excel template
- Check required fields are properly formatted
- Ensure IC numbers are stored as text format

### System Maintenance

**Regular Tasks**:
- Monitor application logs for errors
- Backup database regularly
- Update user permissions when roles change
- Clean up cancelled or expired tickets

**Performance Optimization**:
- Enable Redis caching for sessions
- Configure queue workers for email processing
- Optimize database indexes for large datasets

## 🚀 Deployment

### Production Setup

1. **Server Requirements**:
   - PHP 8.1+ with required extensions
   - MySQL 8.0+ or PostgreSQL 13+
   - Web server (Apache/Nginx)
   - SSL certificate for HTTPS

2. **Deployment Steps**:
   ```bash
   # Optimize for production
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   
   # Set proper permissions
   chmod -R 755 storage/
   chmod -R 755 bootstrap/cache/
   ```

3. **Security Checklist**:
   - Set `APP_DEBUG=false`
   - Configure firewall rules
   - Enable HTTPS redirect
   - Set up automated backups
   - Configure log rotation

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 License

This project is proprietary software developed for KKHS (Kota Kinabalu High School).

## 📞 Support

For technical support or feature requests:
- Create an issue in the repository
- Contact the development team
- Refer to the comprehensive user manual

---

**System Version**: 2.0  
**Last Updated**: December 2024  
**Laravel Version**: 11.x  
**PHP Version**: 8.1+ 