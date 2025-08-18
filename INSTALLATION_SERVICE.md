# Installation Service Documentation

The Laravel One-Click Installer package includes a powerful **InstallationService** that provides comprehensive installation status management and automatic redirect functionality.

## Features

- ✅ **Multiple Check Methods**: Environment variables, file existence, or database records
- 🔄 **Automatic Redirects**: Smart redirection to installer when app is not installed
- 🛠️ **Management Commands**: Easy installation status management via Artisan commands
- 🎯 **Intelligent Detection**: Skips redirects for AJAX, API, and asset requests
- 📊 **Detailed Reporting**: Comprehensive installation status information

## Quick Start

### Basic Usage

```php
use Clevpro\LaravelOneClickInstaller\Services\InstallationService;

// Check if application is installed
$installer = app(InstallationService::class);
$isInstalled = $installer->isInstalled();

// Get detailed installation info
$info = $installer->getInstallationInfo();
```

### Using the Facade

```php
use Clevpro\LaravelOneClickInstaller\Facades\Installer;

// Check installation status
if (Installer::isInstalled()) {
    // App is installed
} else {
    // Redirect to installer
    return redirect(Installer::getInstallerUrl());
}

// Get installation information
$info = Installer::getInstallationInfo();
```

## Installation Check Methods

Configure the check method in `config/one-click-installer.php`:

### Environment Variable Method (Default)
```php
'installation_check' => [
    'method' => 'env',
    'env_key' => 'APP_INSTALLED', // Checks for APP_INSTALLED=true in .env
],
```

### File-based Method
```php
'installation_check' => [
    'method' => 'file',
    'file_path' => storage_path('app/installed'), // Checks if file exists
],
```

### Database Method
```php
'installation_check' => [
    'method' => 'database',
    'database_table' => 'settings',
    'database_key' => 'app_installed', // Checks for record with key=app_installed, value=true
],
```

## Available Methods

### Status Check Methods
```php
$installer = app(InstallationService::class);

// Main installation check (uses configured method)
$isInstalled = $installer->isInstalled();

// Specific method checks
$envCheck = $installer->checkByEnvironment();
$fileCheck = $installer->checkByFile();
$dbCheck = $installer->checkByDatabase();
```

### Installation Management
```php
// Mark application as installed
$installer->markAsInstalled();

// Mark using specific methods
$installer->markInstalledByEnvironment();
$installer->markInstalledByFile();
$installer->markInstalledByDatabase();

// Reset installation status (for testing)
$installer->resetInstallation();
```

### Redirect Logic
```php
// Check if should redirect to installer
if ($installer->shouldRedirectToInstaller()) {
    return redirect($installer->getInstallerUrl());
}

// Check if current route is installer route
$isInstallerRoute = $installer->isInstallerRoute();

// Get installer URL
$installerUrl = $installer->getInstallerUrl(); // /install or custom prefix
```

### Information Methods
```php
// Get comprehensive installation information
$info = $installer->getInstallationInfo();
/*
Returns:
[
    'is_installed' => false,
    'check_method' => 'env',
    'installer_url' => 'http://localhost/install',
    'should_redirect' => true,
    'current_route' => 'dashboard',
    'installation_details' => [
        'env_key' => 'APP_INSTALLED',
        'env_value' => false,
    ],
]
*/
```

## Artisan Commands

### Check Installation Status
```bash
# Show detailed installation status
php artisan installer:status

# Example output:
# 🔍 Installation Status Report
# 
# ❌ Application is NOT INSTALLED
# 
# 📋 Configuration:
#    Check Method: env
#    Installer URL: http://localhost/install
#    Current Route: /
#    ⚠️  Will redirect to installer
# 
# 🔧 Method Details:
#    Env Key: APP_INSTALLED
#    Env Value: ❌
```

### Mark as Installed
```bash
# Mark application as installed (bypasses installer wizard)
php artisan installer:status --mark-installed
```

### Reset Installation
```bash
# Reset installation status (for testing/development)
php artisan installer:status --reset
```

## Middleware Integration

### Automatic Installer Redirect
Add to your main application routes to automatically redirect non-installed apps:

```php
// routes/web.php
Route::middleware(['installer.check'])->group(function () {
    // Your application routes
    Route::get('/', [HomeController::class, 'index']);
    Route::get('/dashboard', [DashboardController::class, 'index']);
    // ... other routes
});
```

### Protect Installer Routes
The installer routes are automatically protected with the `installer.redirect` middleware to prevent access when app is already installed.

## Custom Middleware Example

Create custom middleware using the service:

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Clevpro\LaravelOneClickInstaller\Services\InstallationService;

class CheckInstallation
{
    public function __construct(private InstallationService $installer) {}

    public function handle(Request $request, Closure $next)
    {
        // Skip for API routes
        if ($request->is('api/*')) {
            return $next($request);
        }

        // Redirect if not installed
        if ($this->installer->shouldRedirectToInstaller()) {
            return redirect($this->installer->getInstallerUrl())
                ->with('message', 'Please complete the installation process.');
        }

        return $next($request);
    }
}
```

## Integration with Application Logic

### Service Provider Integration
```php
// app/Providers/AppServiceProvider.php
use Clevpro\LaravelOneClickInstaller\Services\InstallationService;

public function boot()
{
    $installer = app(InstallationService::class);
    
    // Only register certain services if app is installed
    if ($installer->isInstalled()) {
        // Register production services
        $this->registerProductionServices();
    } else {
        // Register minimal services for installer
        $this->registerInstallerServices();
    }
}
```

### Conditional Feature Loading
```php
// In your application
use Clevpro\LaravelOneClickInstaller\Facades\Installer;

// Only load heavy features if installed
if (Installer::isInstalled()) {
    // Load plugins, themes, etc.
    $this->loadPlugins();
    $this->initializeThemes();
} else {
    // Show minimal interface or redirect
    return view('installer.required');
}
```

## Error Handling

The service gracefully handles various error conditions:

- **Database Connection Errors**: Returns `false` for database checks if connection fails
- **File Permission Errors**: Returns `false` if unable to read/write installation files
- **Environment File Issues**: Handles missing or unreadable `.env` files
- **Configuration Errors**: Falls back to environment method if invalid method specified

## Best Practices

### Development
```php
// In development, easily reset installation
if (app()->environment('local')) {
    Installer::resetInstallation();
}
```

### Production Deployment
```php
// Ensure app is marked as installed in production
if (app()->environment('production') && !Installer::isInstalled()) {
    // Auto-mark as installed or show maintenance page
    Installer::markAsInstalled();
}
```

### Testing
```php
// In tests, control installation status
public function test_installer_redirects_when_not_installed()
{
    Installer::resetInstallation();
    
    $response = $this->get('/dashboard');
    $response->assertRedirect('/install');
}
```

## Configuration Options

See `config/one-click-installer.php` for all available configuration options:

- **Route Prefix**: Customize installer URL (`install`, `setup`, etc.)
- **Middleware**: Configure which middleware applies to installer routes
- **Check Method**: Choose between `env`, `file`, or `database` checking
- **Redirect Target**: Where to redirect after successful installation
- **UI Customization**: App name, colors, logos for installer interface

## Troubleshooting

### Installation Status Not Detected
1. Check configuration method in `config/one-click-installer.php`
2. Verify file permissions for file-based checking
3. Confirm database connection for database-based checking
4. Use `php artisan installer:status` for detailed diagnostics

### Redirects Not Working
1. Ensure middleware is properly registered
2. Check route group configuration
3. Verify installer routes are not cached: `php artisan route:clear`

### Command Not Found
1. Run `composer dump-autoload` to refresh autoloader
2. Check if package is properly registered in `config/app.php` or `bootstrap/providers.php`
3. Clear config cache: `php artisan config:clear`

This service provides a robust foundation for managing application installation status with flexible configuration options and comprehensive error handling.