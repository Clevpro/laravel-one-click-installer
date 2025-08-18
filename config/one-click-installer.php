<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Installation Route Prefix
    |--------------------------------------------------------------------------
    |
    | This value defines the route prefix for the installer. You can change
    | this to any value you prefer. Default is 'install'.
    |
    */
    'route_prefix' => env('INSTALLER_ROUTE_PREFIX', 'install'),

    /*
    |--------------------------------------------------------------------------
    | Installer Middleware
    |--------------------------------------------------------------------------
    |
    | This array defines the middleware that should be applied to the
    | installer routes. You can add or remove middleware as needed.
    |
    */
    'middleware' => ['web'],

    /*
    |--------------------------------------------------------------------------
    | Installation Status
    |--------------------------------------------------------------------------
    |
    | This setting determines how the package checks if the application
    | is already installed. Default is checking the APP_INSTALLED env variable.
    |
    */
    'installation_check' => [
        'method' => 'env', // env, file, database
        'env_key' => 'APP_INSTALLED',
        'file_path' => storage_path('app/installed'),
        'database_table' => 'settings',
        'database_key' => 'app_installed',
    ],

    /*
    |--------------------------------------------------------------------------
    | Database Requirements
    |--------------------------------------------------------------------------
    |
    | These are the minimum requirements for the database connection.
    |
    */
    'database' => [
        'required_fields' => [
            'DB_HOST',
            'DB_PORT',
            'DB_DATABASE',
            'DB_USERNAME',
        ],
        'test_connection' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin User Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for the admin user creation during installation.
    |
    */
    'admin_user' => [
        'model' => \App\Models\User::class,
        'default_role' => 'admin',
        'email_verification' => false,
        'force_password_reset' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Post Installation Actions
    |--------------------------------------------------------------------------
    |
    | Actions to perform after successful installation.
    |
    */
    'post_installation' => [
        'clear_cache' => true,
        'run_migrations' => true,
        'run_seeders' => true,
        'create_storage_link' => true,
        'generate_app_key' => true,
        'redirect_to' => '/admin/dashboard', // Where to redirect after installation
    ],

    /*
    |--------------------------------------------------------------------------
    | UI Customization
    |--------------------------------------------------------------------------
    |
    | Customize the installer UI appearance.
    |
    */
    'ui' => [
        'app_name' => env('APP_NAME', 'Laravel Application'),
        'theme_color' => '#10b981', // Green
        'logo_url' => null,
        'favicon_url' => null,
        'show_progress_bar' => true,
        'animation_duration' => 300,
        'auto_save' => false, // Auto-save form data to localStorage
    ],
];