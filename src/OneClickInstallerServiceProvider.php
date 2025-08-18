<?php

namespace Clevpro\LaravelOneClickInstaller;

use Illuminate\Support\ServiceProvider;
use Clevpro\LaravelOneClickInstaller\Http\Middleware\RedirectIfInstalled;
use Clevpro\LaravelOneClickInstaller\Http\Middleware\RedirectToInstaller;
use Clevpro\LaravelOneClickInstaller\Services\InstallationService;
use Clevpro\LaravelOneClickInstaller\Console\Commands\InstallationStatusCommand;

class OneClickInstallerServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/one-click-installer.php',
            'one-click-installer'
        );

        // Register the InstallationService as a singleton
        $this->app->singleton(InstallationService::class, function () {
            return new InstallationService();
        });

        // Bind with an alias for easier access
        $this->app->alias(InstallationService::class, 'installer.service');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/installer.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'installer');

        // Register middleware
        $this->app['router']->aliasMiddleware('installer.redirect', RedirectIfInstalled::class);
        $this->app['router']->aliasMiddleware('installer.check', RedirectToInstaller::class);

        if ($this->app->runningInConsole()) {
            // Register console commands
            $this->commands([
                InstallationStatusCommand::class,
            ]);

            $this->publishes([
                __DIR__.'/../config/one-click-installer.php' => config_path('one-click-installer.php'),
            ], 'installer-config');

            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/installer'),
            ], 'installer-views');

            $this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/installer'),
            ], 'installer-assets');

            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'installer-migrations');
        }
    }
}