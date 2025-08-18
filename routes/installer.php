<?php

use Illuminate\Support\Facades\Route;
use Clevpro\LaravelOneClickInstaller\Http\Controllers\InstallerController;
use Clevpro\LaravelOneClickInstaller\Http\Controllers\AssetController;

/*
|--------------------------------------------------------------------------
| Laravel One-Click Installer Routes
|--------------------------------------------------------------------------
|
| These routes handle the installation wizard process. They are protected
| by middleware to prevent access after installation is complete.
|
*/

$routePrefix = config('one-click-installer.route_prefix', 'install');
$middleware = config('one-click-installer.middleware', ['web']);

// Add the installer redirect middleware to the middleware stack
$middleware[] = 'installer.redirect';

Route::prefix($routePrefix)
    ->middleware($middleware)
    ->name('installer.')
    ->group(function () {
        
        // Step 1: Welcome screen
        Route::get('/', [InstallerController::class, 'welcome'])
            ->name('welcome');
        
        // Step 2: Environment setup
        Route::get('/environment', [InstallerController::class, 'environment'])
            ->name('environment');
        
        Route::post('/environment', [InstallerController::class, 'storeEnvironment'])
            ->name('environment.store');
        
        // Step 3: Database migrations
        Route::get('/migrations', [InstallerController::class, 'migrations'])
            ->name('migrations');
        
        Route::post('/migrations', [InstallerController::class, 'runMigrations'])
            ->name('migrations.run');
        
        // Step 4: Admin account creation
        Route::get('/admin', [InstallerController::class, 'admin'])
            ->name('admin');
        
        Route::post('/admin', [InstallerController::class, 'storeAdmin'])
            ->name('admin.store');
        
        // Step 5: Installation complete
        Route::get('/finish', [InstallerController::class, 'finish'])
            ->name('finish');
    });

// Asset routes (outside the installer middleware to avoid redirect issues)
Route::prefix($routePrefix . '/assets')
    ->name('installer.assets.')
    ->group(function () {
        Route::get('/css/{filename}', [AssetController::class, 'css'])
            ->name('css')
            ->where('filename', '.*\.css$');
        
        Route::get('/js/{filename}', [AssetController::class, 'js'])
            ->name('js')
            ->where('filename', '.*\.js$');
    });