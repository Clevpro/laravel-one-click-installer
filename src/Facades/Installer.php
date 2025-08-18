<?php

namespace Clevpro\LaravelOneClickInstaller\Facades;

use Illuminate\Support\Facades\Facade;
use Clevpro\LaravelOneClickInstaller\Services\InstallationService;

/**
 * @method static bool isInstalled()
 * @method static bool checkByEnvironment()
 * @method static bool checkByFile()
 * @method static bool checkByDatabase()
 * @method static bool markAsInstalled()
 * @method static bool markInstalledByEnvironment()
 * @method static bool markInstalledByFile()
 * @method static bool markInstalledByDatabase()
 * @method static string getInstallerUrl()
 * @method static bool shouldRedirectToInstaller()
 * @method static bool isInstallerRoute()
 * @method static array getInstallationInfo()
 * @method static bool resetInstallation()
 *
 * @see \Clevpro\LaravelOneClickInstaller\Services\InstallationService
 */
class Installer extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return InstallationService::class;
    }
}