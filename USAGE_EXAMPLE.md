# Laravel One-Click Installer - Usage Example

This document provides a complete example of how to integrate and use the Laravel One-Click Installer package in your Laravel application.

## Step-by-Step Integration

### 1. Install the Package

```bash
composer require clevpro/laravel-one-click-installer
```

### 2. Publish Assets

```bash
# Publish all assets
php artisan vendor:publish --provider="Clevpro\LaravelOneClickInstaller\OneClickInstallerServiceProvider"

# Or publish specific assets
php artisan vendor:publish --tag=installer-config
php artisan vendor:publish --tag=installer-views
php artisan vendor:publish --tag=installer-assets
```

### 3. Configure the Package

Edit `config/one-click-installer.php`:

```php
<?php

return [
    'route_prefix' => 'setup', // Change from 'install' to 'setup'
    
    'ui' => [
        'app_name' => 'My Amazing Laravel App',
        'theme_color' => '#8b5cf6', // Purple theme
        'logo_url' => '/images/logo.png',
        'show_progress_bar' => true,
    ],
    
    'admin_user' => [
        'model' => \App\Models\User::class,
        'default_role' => 'super-admin',
        'email_verification' => false,
    ],
    
    'post_installation' => [
        'redirect_to' => '/admin/dashboard',
        'run_seeders' => true,
        'create_storage_link' => true,
    ],
];
```

### 4. Set Up Your User Model

Ensure your User model is properly configured:

```php
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles; // Optional

class User extends Authenticatable
{
    use HasRoles; // If using Spatie Permission
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
```

### 5. Create Initial Migration (if needed)

If you don't have a users table yet:

```bash
php artisan make:migration create_users_table
```

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
```

### 6. Add Installation Check to Middleware

Add the installer middleware to your main application (optional):

```php
// app/Http/Kernel.php
protected $middlewareGroups = [
    'web' => [
        // ... other middleware
        \Clevpro\LaravelOneClickInstaller\Http\Middleware\RedirectIfInstalled::class,
    ],
];
```

### 7. Usage Workflow

#### Access the Installer
Navigate to: `http://yourapp.com/setup` (or `/install` if using default)

#### Step 1: Welcome Screen
- Shows installation overview
- Lists what will be configured
- Provides system requirements

#### Step 2: Environment Setup
```
Application Name: My Amazing Laravel App
Application URL: https://myapp.com
Database Host: localhost
Database Port: 3306
Database Name: my_laravel_db
Database Username: root
Database Password: [your-password]
```

#### Step 3: Database Setup
- Automatically runs migrations
- Seeds initial data
- Creates storage links
- Clears caches

#### Step 4: Admin Account
```
Full Name: John Administrator
Email: admin@myapp.com
Password: SecurePassword123!
Confirm Password: SecurePassword123!
```

#### Step 5: Installation Complete
- Shows success message
- Provides next steps
- Redirects to admin dashboard

### 8. Post-Installation

After installation, your `.env` file will contain:

```env
APP_NAME="My Amazing Laravel App"
APP_URL="https://myapp.com"
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=my_laravel_db
DB_USERNAME=root
DB_PASSWORD=your-password
APP_INSTALLED=true
```

### 9. Customization Examples

#### Custom Views

After publishing views, customize them in `resources/views/vendor/installer/`:

```blade
{{-- resources/views/vendor/installer/welcome.blade.php --}}
@extends('installer::layout')

@section('content')
<div class="text-center">
    <h1 class="text-4xl font-bold text-purple-600 mb-4">
        Welcome to {{ $appName }}! 🚀
    </h1>
    <!-- Your custom welcome content -->
</div>
@endsection
```

#### Custom Styling

```css
/* public/vendor/installer/custom.css */
:root {
    --installer-primary-color: #8b5cf6;
}

.installer-custom-header {
    background: linear-gradient(135deg, #8b5cf6 0%, #3b82f6 100%);
}
```

#### Custom Post-Installation Logic

```php
// app/Providers/AppServiceProvider.php
use Clevpro\LaravelOneClickInstaller\Events\InstallationCompleted;

public function boot()
{
    Event::listen(InstallationCompleted::class, function ($event) {
        // Send welcome email
        Mail::to($event->adminEmail)->send(new WelcomeEmail());
        
        // Log installation
        Log::info('New installation completed', [
            'app_name' => $event->appName,
            'admin_email' => $event->adminEmail,
            'installed_at' => now(),
        ]);
        
        // Create default settings
        Setting::create([
            'key' => 'site_initialized',
            'value' => true,
        ]);
    });
}
```

### 10. Security Considerations

#### Production Deployment

1. **Remove installer access:**
```php
// routes/web.php - Add this check
if (!env('APP_INSTALLED', false)) {
    // Installer routes are automatically loaded
} else {
    // Redirect to 404 or home page
    Route::get('/setup', function () {
        abort(404);
    });
}
```

2. **File permissions:**
```bash
chmod 644 .env
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

3. **Environment variables:**
```env
APP_DEBUG=false
APP_INSTALLED=true
```

### 11. Troubleshooting

#### Common Issues

**Database Connection Failed:**
```bash
# Check database service
sudo service mysql status

# Test connection manually
mysql -h localhost -u root -p
```

**Permission Denied:**
```bash
# Fix Laravel permissions
sudo chown -R www-data:www-data storage/
sudo chmod -R 755 storage/
```

**View Not Found:**
```bash
# Clear view cache
php artisan view:clear

# Republish views
php artisan vendor:publish --tag=installer-views --force
```

### 12. Advanced Usage

#### Custom Installation Flow

```php
// config/one-click-installer.php
'custom_steps' => [
    'plugins' => [
        'name' => 'Install Plugins',
        'controller' => 'App\Http\Controllers\PluginInstallController',
        'view' => 'custom.plugin-install',
    ],
],
```

#### Multi-Language Support

```php
// config/one-click-installer.php
'ui' => [
    'language' => 'en', // en, es, fr, de
    'rtl_support' => false,
],
```

This example demonstrates a complete integration of the Laravel One-Click Installer package, from installation to customization and deployment considerations.