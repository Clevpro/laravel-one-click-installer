<?php

namespace Clevpro\LaravelOneClickInstaller\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;
use Exception;

class InstallationService
{
    /**
     * Check if the application is installed
     */
    public function isInstalled(): bool
    {
        $method = config('one-click-installer.installation_check.method', 'env');

        return match ($method) {
            'env' => $this->checkByEnvironment(),
            'file' => $this->checkByFile(),
            'database' => $this->checkByDatabase(),
            default => $this->checkByEnvironment(),
        };
    }

    /**
     * Check installation status by environment variable
     */
    public function checkByEnvironment(): bool
    {
        $envKey = config('one-click-installer.installation_check.env_key', 'APP_INSTALLED');
        $envValue = env($envKey, false);
        
        return $envValue === 'true' || $envValue === true;
    }

    /**
     * Check installation status by file existence
     */
    public function checkByFile(): bool
    {
        $filePath = config('one-click-installer.installation_check.file_path', storage_path('app/installed'));
        return File::exists($filePath);
    }

    /**
     * Check installation status by database record
     */
    public function checkByDatabase(): bool
    {
        try {
            $table = config('one-click-installer.installation_check.database_table', 'settings');
            $key = config('one-click-installer.installation_check.database_key', 'app_installed');

            // Check if database connection exists
            DB::connection()->getPdo();

            // Check if table exists
            if (!$this->tableExists($table)) {
                return false;
            }

            // Check for installation record
            $result = DB::table($table)->where('key', $key)->value('value');
            return $result === 'true' || $result === '1' || $result === 1 || $result === true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Mark the application as installed
     */
    public function markAsInstalled(): bool
    {
        $method = config('one-click-installer.installation_check.method', 'env');

        return match ($method) {
            'env' => $this->markInstalledByEnvironment(),
            'file' => $this->markInstalledByFile(),
            'database' => $this->markInstalledByDatabase(),
            default => $this->markInstalledByEnvironment(),
        };
    }

    /**
     * Mark as installed by updating environment file
     */
    public function markInstalledByEnvironment(): bool
    {
        try {
            $envKey = config('one-click-installer.installation_check.env_key', 'APP_INSTALLED');
            $envPath = base_path('.env');

            if (!File::exists($envPath)) {
                return false;
            }

            $envContent = File::get($envPath);

            // Check if the key already exists
            if (preg_match("/^{$envKey}=.*$/m", $envContent)) {
                // Update existing key
                $envContent = preg_replace("/^{$envKey}=.*$/m", "{$envKey}=true", $envContent);
            } else {
                // Add new key
                $envContent .= "\n{$envKey}=true\n";
            }

            File::put($envPath, $envContent);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Mark as installed by creating a file
     */
    public function markInstalledByFile(): bool
    {
        try {
            $filePath = config('one-click-installer.installation_check.file_path', storage_path('app/installed'));
            $directory = dirname($filePath);

            // Create directory if it doesn't exist
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            // Create the installation file with timestamp
            $content = [
                'installed_at' => now()->toISOString(),
                'installed_by' => 'laravel-one-click-installer',
                'version' => config('one-click-installer.version', '1.0.0'),
            ];

            File::put($filePath, json_encode($content, JSON_PRETTY_PRINT));
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Mark as installed by creating/updating database record
     */
    public function markInstalledByDatabase(): bool
    {
        try {
            $table = config('one-click-installer.installation_check.database_table', 'settings');
            $key = config('one-click-installer.installation_check.database_key', 'app_installed');

            // Check database connection
            DB::connection()->getPdo();

            // Create table if it doesn't exist
            if (!$this->tableExists($table)) {
                $this->createSettingsTable($table);
            }

            // Insert or update the installation record
            DB::table($table)->updateOrInsert(
                ['key' => $key],
                [
                    'value' => 'true',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Get the installer route URL
     */
    public function getInstallerUrl(): string
    {
        $prefix = config('one-click-installer.route_prefix', 'install');
        return url($prefix);
    }

    /**
     * Check if we should redirect to installer
     */
    public function shouldRedirectToInstaller(): bool
    {
        // Don't redirect if already on installer routes
        if ($this->isInstallerRoute()) {
            return false;
        }

        // Don't redirect if app is already installed
        if ($this->isInstalled()) {
            return false;
        }

        return true;
    }

    /**
     * Check if current request is for installer routes
     */
    public function isInstallerRoute(): bool
    {
        $prefix = config('one-click-installer.route_prefix', 'install');
        $currentPath = request()->path();

        return str_starts_with($currentPath, $prefix) || $currentPath === $prefix;
    }

    /**
     * Get installation progress information
     */
    public function getInstallationInfo(): array
    {
        $method = config('one-click-installer.installation_check.method', 'env');
        
        return [
            'is_installed' => $this->isInstalled(),
            'check_method' => $method,
            'installer_url' => $this->getInstallerUrl(),
            'should_redirect' => $this->shouldRedirectToInstaller(),
            'current_route' => request()->path(),
            'installation_details' => $this->getInstallationDetails(),
        ];
    }

    /**
     * Get detailed installation information based on method
     */
    private function getInstallationDetails(): array
    {
        $method = config('one-click-installer.installation_check.method', 'env');

        return match ($method) {
            'env' => [
                'env_key' => config('one-click-installer.installation_check.env_key', 'APP_INSTALLED'),
                'env_value' => env(config('one-click-installer.installation_check.env_key', 'APP_INSTALLED')),
            ],
            'file' => [
                'file_path' => config('one-click-installer.installation_check.file_path', storage_path('app/installed')),
                'file_exists' => File::exists(config('one-click-installer.installation_check.file_path', storage_path('app/installed'))),
            ],
            'database' => [
                'table' => config('one-click-installer.installation_check.database_table', 'settings'),
                'key' => config('one-click-installer.installation_check.database_key', 'app_installed'),
                'table_exists' => $this->tableExists(config('one-click-installer.installation_check.database_table', 'settings')),
            ],
            default => [],
        };
    }

    /**
     * Check if a database table exists
     */
    private function tableExists(string $table): bool
    {
        try {
            return DB::getSchemaBuilder()->hasTable($table);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Create the settings table for database-based installation checking
     */
    private function createSettingsTable(string $table): void
    {
        DB::statement("
            CREATE TABLE IF NOT EXISTS `{$table}` (
                `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                `key` varchar(255) NOT NULL,
                `value` text,
                `created_at` timestamp NULL DEFAULT NULL,
                `updated_at` timestamp NULL DEFAULT NULL,
                PRIMARY KEY (`id`),
                UNIQUE KEY `{$table}_key_unique` (`key`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
    }

    /**
     * Reset installation status (for testing purposes)
     */
    public function resetInstallation(): bool
    {
        $method = config('one-click-installer.installation_check.method', 'env');

        return match ($method) {
            'env' => $this->resetEnvironmentInstallation(),
            'file' => $this->resetFileInstallation(),
            'database' => $this->resetDatabaseInstallation(),
            default => $this->resetEnvironmentInstallation(),
        };
    }

    /**
     * Reset environment-based installation
     */
    private function resetEnvironmentInstallation(): bool
    {
        try {
            $envKey = config('one-click-installer.installation_check.env_key', 'APP_INSTALLED');
            $envPath = base_path('.env');

            if (!File::exists($envPath)) {
                return false;
            }

            $envContent = File::get($envPath);
            $envContent = preg_replace("/^{$envKey}=.*$/m", "{$envKey}=false", $envContent);
            File::put($envPath, $envContent);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Reset file-based installation
     */
    private function resetFileInstallation(): bool
    {
        try {
            $filePath = config('one-click-installer.installation_check.file_path', storage_path('app/installed'));
            
            if (File::exists($filePath)) {
                File::delete($filePath);
            }

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Reset database-based installation
     */
    private function resetDatabaseInstallation(): bool
    {
        try {
            $table = config('one-click-installer.installation_check.database_table', 'settings');
            $key = config('one-click-installer.installation_check.database_key', 'app_installed');

            DB::table($table)->where('key', $key)->delete();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}