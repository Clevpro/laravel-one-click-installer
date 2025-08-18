# Laravel One-Click Installer

[![Latest Version on Packagist](https://img.shields.io/packagist/v/clevpro/laravel-one-click-installer.svg?style=flat-square)](https://packagist.org/packages/clevpro/laravel-one-click-installer)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/clevpro/laravel-one-click-installer/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/clevpro/laravel-one-click-installer/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/clevpro/laravel-one-click-installer/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/clevpro/laravel-one-click-installer/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/clevpro/laravel-one-click-installer.svg?style=flat-square)](https://packagist.org/packages/clevpro/laravel-one-click-installer)
![Laravel Version](https://img.shields.io/badge/Laravel-11%20%7C%2012-orange.svg?style=flat-square)
![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-blue.svg?style=flat-square)
![License](https://img.shields.io/badge/license-MIT-green.svg?style=flat-square)

A beautiful and user-friendly one-click installer package for Laravel applications, featuring a modern **TailwindCSS-based UI**, **comprehensive 5-step installation wizard**, and **intelligent installation management system**.

Perfect for Laravel applications that need a professional installation experience for end users, SaaS applications, or any project requiring guided setup.

## 🎯 Why Choose Laravel One-Click Installer?

- 🚀 **Production Ready**: Battle-tested installation flow with error handling
- 🎨 **Beautiful UI**: Modern TailwindCSS interface that works on all devices  
- 🧠 **Smart Management**: Intelligent installation status detection and management
- 🔒 **Secure**: Built-in security measures and post-installation protection
- ⚡ **Fast Setup**: Get your users up and running in under 5 minutes
- 🛠️ **Developer Friendly**: Comprehensive API, artisan commands, and documentation

## Features

✨ **Beautiful TailwindCSS Interface** - Modern, responsive wizard with progress indicators  
🔒 **Secure Installation Process** - Automatic security measures and post-installation protection  
🗄️ **Database Setup** - Automated migration and seeding with connection testing  
👤 **Admin Account Creation** - Easy administrator account setup with validation  
⚙️ **Environment Configuration** - Interactive .env file setup  
🛡️ **Installation Protection** - Automatic middleware to prevent re-installation  
🔧 **Installation Service** - Comprehensive service for status management and redirects  
📋 **Management Commands** - Artisan commands for installation status control  
📱 **Mobile Responsive** - Works perfectly on all devices  
🎨 **Customizable** - Configurable themes, colors, and branding  
🚀 **Zero-Config Assets** - CSS/JS served directly from package (no publishing required)  

## 📋 Requirements

- **PHP**: 8.2 or higher
- **Laravel**: 11.0 or 12.0  
- **Database**: MySQL, PostgreSQL, SQLite, or SQL Server
- **Web Server**: Apache, Nginx, or Laravel's built-in server

## 🚀 Installation

### Step 1: Install via Composer

```bash
composer require clevpro/laravel-one-click-installer
```

> **Note**: The package will be automatically discovered by Laravel. No manual service provider registration needed!

### Step 2: Publish Package Assets (Optional)

**✨ The package works completely out of the box!** Assets (CSS/JS) are served directly from the package without publishing.

You only need to publish assets if you want to customize the appearance or functionality:

```bash
# Publish all assets at once
php artisan vendor:publish --provider="Clevpro\LaravelOneClickInstaller\OneClickInstallerServiceProvider"

# Or publish specific assets only
php artisan vendor:publish --tag=installer-config   # Configuration file
php artisan vendor:publish --tag=installer-views    # Blade templates  
php artisan vendor:publish --tag=installer-assets   # CSS/JS assets
```

### Step 3: Configure (Optional)

Customize the installer by editing `config/one-click-installer.php`:

```php
return [
    'route_prefix' => 'install',        // Change to 'setup' if preferred
    'ui' => [
        'app_name' => 'My Awesome App', // Your app name
        'theme_color' => '#3b82f6',     // Primary color
        'logo_url' => '/img/logo.png',  // Your logo
    ],
    'admin_user' => [
        'model' => \App\Models\User::class,
        'default_role' => 'admin',
    ],
    'post_installation' => [
        'redirect_to' => '/admin/dashboard',
    ],
];
```

### Step 4: Set Up Application Redirect (Recommended)

Add the installer middleware to automatically redirect users to the installer when the app isn't installed:

```php
// routes/web.php or routes/admin.php
Route::middleware(['installer.check'])->group(function () {
    // Your application routes
    Route::get('/', [HomeController::class, 'index']);
    Route::get('/dashboard', [DashboardController::class, 'index']);
    // ... all your protected routes
});
```

### Step 5: Access the Installer

That's it! Visit your application:

```
http://yourapp.com/install
```

The beautiful 5-step installation wizard will guide you through the setup process.

## 🎮 Usage

### 🚀 Quick Start Guide

Once installed, your users can set up your application in just 5 simple steps:

#### 1️⃣ Access the Installer
Navigate to: `http://yourapp.com/install`

#### 2️⃣ Complete the Installation Wizard

| Step | What It Does | User Input Required |
|------|-------------|-------------------|
| **Welcome** | Introduction & requirements check | None - just click "Get Started" |
| **Environment** | App configuration & database setup | App name, URL, database credentials |
| **Database** | Run migrations & seeders | None - automatic process |
| **Admin Account** | Create admin user | Name, email, password |
| **Finish** | Installation complete! | None - redirects to dashboard |

#### 3️⃣ Enjoy Your Installed Application
After completion, users are automatically redirected to your admin dashboard or specified route.

### 💡 For Developers

#### Check Installation Status
```php
use Clevpro\LaravelOneClickInstaller\Facades\Installer;

if (Installer::isInstalled()) {
    // Application is ready to use
    return view('dashboard');
} else {
    // Redirect to installer
    return redirect(Installer::getInstallerUrl());
}
```

#### Use Artisan Commands
```bash
# Check current installation status
php artisan installer:status

# Mark app as installed (skip wizard)
php artisan installer:status --mark-installed

# Reset for development/testing
php artisan installer:status --reset
```

#### Protect Your Routes
```php
// Automatically redirect to installer if not installed
Route::middleware(['installer.check'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::resource('users', UserController::class);
    // ... your protected routes
});
```

### Installation Steps Details

#### Step 1: Welcome Screen
- Overview of installation process
- System requirements check
- Introduction to setup steps

#### Step 2: Environment Configuration
- Application name and URL setup
- Database connection configuration
- Automatic connection testing
- .env file generation

#### Step 3: Database Setup
- Application key generation
- Database migrations execution
- Seeders execution
- Storage link creation
- Cache clearing

#### Step 4: Admin Account
- Administrator user creation
- Email and password setup
- Role assignment (if using Spatie Permission)
- Account validation

#### Step 5: Installation Complete
- Success confirmation
- Installation summary
- Next steps guidance
- Security recommendations

## Configuration

### Basic Configuration

The main configuration file is located at `config/one-click-installer.php`. Here are the key sections:

```php
'ui' => [
    'app_name' => env('APP_NAME', 'Laravel Application'),
    'theme_color' => '#3b82f6',
    'logo_url' => null,
    'favicon_url' => null,
    'show_progress_bar' => true,
],

'admin_user' => [
    'model' => \App\Models\User::class,
    'default_role' => 'admin',
    'email_verification' => false,
],

'post_installation' => [
    'clear_cache' => true,
    'run_migrations' => true,
    'run_seeders' => true,
    'create_storage_link' => true,
    'redirect_to' => '/admin/dashboard',
],
```

### Route Configuration

Customize the installer routes:

```php
'route_prefix' => env('INSTALLER_ROUTE_PREFIX', 'install'),
'middleware' => ['web'],
```

### Installation Detection

Configure how the package detects if installation is complete:

```php
'installation_check' => [
    'method' => 'env', // env, file, database
    'env_key' => 'APP_INSTALLED',
    'file_path' => storage_path('app/installed'),
    'database_table' => 'settings',
    'database_key' => 'app_installed',
],
```

## Customization

### Custom Views

Publish and customize the installer views:

```bash
php artisan vendor:publish --tag=installer-views
```

Views will be published to `resources/views/vendor/installer/`:
- `layout.blade.php` - Main layout template
- `welcome.blade.php` - Welcome screen
- `environment.blade.php` - Environment setup
- `migrations.blade.php` - Database setup
- `admin.blade.php` - Admin creation
- `finish.blade.php` - Completion screen

### Custom Styling

The installer uses TailwindCSS by default. You can customize the appearance by:

1. Modifying the configuration:
```php
'ui' => [
    'theme_color' => '#your-color',
    'logo_url' => '/path/to/your/logo.png',
],
```

2. Overriding the CSS in your published views
3. Adding custom JavaScript for enhanced interactions

### Custom User Model

If you're using a custom User model:

```php
'admin_user' => [
    'model' => \App\Models\CustomUser::class,
    'default_role' => 'super-admin',
],
```

## Security Features

### Automatic Protection

- **Post-Installation Middleware**: Automatically prevents access to installer after completion
- **Environment Validation**: Validates database connections before proceeding
- **Input Sanitization**: All user inputs are validated and sanitized
- **Password Security**: Enforces strong password requirements

### Manual Security Steps

After installation, consider these additional security measures:

1. **Remove Installer Files** (Production):
```bash
# Remove installer routes (optional)
rm routes/installer.php

# Or restrict access via web server configuration
```

2. **File Permissions**:
```bash
chmod 644 .env
chmod 755 storage/ -R
```

3. **Environment Variables**:
```bash
# Ensure APP_INSTALLED is set to true
APP_INSTALLED=true
APP_DEBUG=false
```

## Advanced Usage

### Custom Post-Installation Actions

You can add custom actions after installation by listening for events or extending the controller:

```php
// In your AppServiceProvider
use Clevpro\LaravelOneClickInstaller\Events\InstallationCompleted;

Event::listen(InstallationCompleted::class, function ($event) {
    // Your custom post-installation logic
    Log::info('Installation completed for: ' . $event->appName);
});
```

### Integration with Existing Applications

If adding to an existing Laravel application:

1. Ensure your User model is properly configured
2. Set up any required roles/permissions
3. Customize the redirect path after installation
4. Consider backing up existing data before running

### Multiple Environment Support

Configure different settings per environment:

```php
// config/one-click-installer.php
'post_installation' => [
    'redirect_to' => env('APP_ENV') === 'production' 
        ? '/admin/dashboard' 
        : '/admin/setup',
],
```

## Troubleshooting

### Common Issues

**1. Permission Denied Errors**
```bash
# Fix file permissions
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

**2. Database Connection Failed**
- Verify database credentials
- Ensure database exists
- Check database server is running
- Verify network connectivity

**3. Class Not Found Errors**
```bash
# Clear and regenerate autoloader
composer dump-autoload
php artisan config:clear
php artisan cache:clear
```

**4. View Not Found**
```bash
# Republish views
php artisan vendor:publish --tag=installer-views --force
```

### Debug Mode

Enable debug information by setting:

```env
APP_DEBUG=true
```

### 🆘 Getting Help

- 📖 **Documentation**: Check the detailed configuration options in this README
- 🐛 **Report Issues**: Found a bug? [Open an issue](https://github.com/clevpro/laravel-one-click-installer/issues)
- 💬 **Ask Questions**: Get help in [GitHub Discussions](https://github.com/clevpro/laravel-one-click-installer/discussions)
- 📧 **Contact Support**: For enterprise support, contact us at support@clevpro.com

## API Reference

### Available Routes

| Route | Method | Description |
|-------|--------|-------------|
| `/install` | GET | Welcome screen |
| `/install/environment` | GET/POST | Environment setup |
| `/install/migrations` | GET/POST | Database setup |
| `/install/admin` | GET/POST | Admin creation |
| `/install/finish` | GET | Completion screen |

### Middleware

- `installer.redirect` - Prevents access to installer routes when app is already installed
- `installer.check` - Redirects non-installed apps to installer wizard

### Installation Service

The package includes a comprehensive `InstallationService` for status management:

```php
use Clevpro\LaravelOneClickInstaller\Services\InstallationService;
use Clevpro\LaravelOneClickInstaller\Facades\Installer;

// Check if installed
$installer = app(InstallationService::class);
$isInstalled = $installer->isInstalled();

// Using the facade
$isInstalled = Installer::isInstalled();

// Get detailed installation info
$info = Installer::getInstallationInfo();

// Mark as installed manually
Installer::markAsInstalled();

// Reset installation (for testing)
Installer::resetInstallation();
```

### Artisan Commands

```bash
# Check installation status
php artisan installer:status

# Mark as installed
php artisan installer:status --mark-installed

# Reset installation status
php artisan installer:status --reset
```

For comprehensive documentation on the Installation Service, see [INSTALLATION_SERVICE.md](INSTALLATION_SERVICE.md).

## 🛠️ Development & Contributing

We welcome contributions! Here's how you can help improve the Laravel One-Click Installer.

### 📋 Contributing Guidelines

1. **Fork the Repository**: Click the "Fork" button on [GitHub](https://github.com/clevpro/laravel-one-click-installer)
2. **Create a Feature Branch**: `git checkout -b feature/amazing-feature`
3. **Make Your Changes**: Follow our coding standards
4. **Add Tests**: Ensure your changes are tested
5. **Commit Changes**: `git commit -m 'Add amazing feature'`
6. **Push to Branch**: `git push origin feature/amazing-feature`
7. **Submit Pull Request**: Open a PR with a clear description

### 🧪 Testing

```bash
# Install development dependencies
composer install

# Run the test suite
composer test

# Run tests with coverage report
composer test-coverage

# Run specific test file
composer test tests/Feature/InstallationServiceTest.php
```

### ✨ Code Quality

```bash
# Check code style
composer cs-check

# Fix code style automatically
composer cs-fix

# Run static analysis
composer analyse
```

### 🏗️ Local Development Setup

```bash
# Clone your fork
git clone https://github.com/your-username/laravel-one-click-installer.git
cd laravel-one-click-installer

# Install dependencies
composer install

# Create a test Laravel app
composer create-project laravel/laravel test-app
cd test-app

# Add your local package
composer config repositories.local path ../
composer require clevpro/laravel-one-click-installer:@dev
```

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for a detailed list of changes.

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).

## 💰 Sponsors & Credits

### 🏆 Sponsors

This project is made possible by our sponsors. Become a sponsor to get your logo here!

[![Sponsor](https://img.shields.io/badge/Sponsor-❤️-ff69b4?style=for-the-badge)](https://github.com/sponsors/clevpro)

### 👥 Credits

- **[Clevpro Team](https://clevpro.com)** - Package development and maintenance
- **[Laravel Community](https://laravel.com)** - Amazing framework and ecosystem
- **[TailwindCSS](https://tailwindcss.com)** - Beautiful utility-first CSS framework
- **Contributors** - All the amazing developers who contributed to this project

### 🔗 Related Projects

- **[Laravel Framework](https://laravel.com)** - The PHP framework for web artisans
- **[Laravel Breeze](https://laravel.com/docs/starter-kits#laravel-breeze)** - Laravel's official starter kit
- **[Spatie Laravel Permission](https://spatie.be/docs/laravel-permission)** - Permission management

## 💖 Support the Project

If you find this package helpful, please consider supporting the project:

### ⭐ Give it a Star
Star the repository on [GitHub](https://github.com/clevpro/laravel-one-click-installer) if you find it useful!

### 🐛 Report Issues
Help improve the package by [reporting bugs](https://github.com/clevpro/laravel-one-click-installer/issues).

### 💡 Suggest Features
Share your ideas in [GitHub Discussions](https://github.com/clevpro/laravel-one-click-installer/discussions).

### 🤝 Contribute
Read our [Contributing Guide](CONTRIBUTING.md) to get started with contributing.

### 💰 Sponsor Development
Support continued development through [GitHub Sponsors](https://github.com/sponsors/clevpro).

### 📢 Spread the Word
Share the project with your network and help others discover it!

## 📄 License

The Laravel One-Click Installer is open-sourced software licensed under the [MIT license](LICENSE).

## 🙏 Thank You

Thank you to all contributors and users who make this project possible. Your feedback, contributions, and support drive continuous improvement and help create better tools for the Laravel community.

---

<div align="center">

**Made with ❤️ by [Clevpro](https://clevpro.com)**

[![Follow on GitHub](https://img.shields.io/github/followers/clevpro?style=social)](https://github.com/clevpro)
[![Twitter Follow](https://img.shields.io/twitter/follow/clevpro?style=social)](https://twitter.com/clevpro)

</div>