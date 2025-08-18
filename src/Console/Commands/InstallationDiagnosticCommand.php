<?php

namespace Clevpro\LaravelOneClickInstaller\Console\Commands;

use Illuminate\Console\Command;
use Clevpro\LaravelOneClickInstaller\Services\InstallationService;

class InstallationDiagnosticCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'installer:diagnose';

    /**
     * The console command description.
     */
    protected $description = 'Diagnose installation status and configuration';

    /**
     * Execute the console command.
     */
    public function handle(InstallationService $installationService): int
    {
        $this->info('Laravel One-Click Installer - Diagnostic Report');
        $this->line('================================================');

        // Get installation info
        $info = $installationService->getInstallationInfo();

        $this->line('Installation Status:');
        $this->line("  Is Installed: " . ($info['is_installed'] ? 'YES' : 'NO'));
        $this->line("  Check Method: " . $info['check_method']);
        $this->line("  Should Redirect: " . ($info['should_redirect'] ? 'YES' : 'NO'));
        $this->line("  Current Route: " . $info['current_route']);
        $this->line("  Installer URL: " . $info['installer_url']);

        $this->line('');
        $this->line('Configuration:');
        
        $method = config('one-click-installer.installation_check.method', 'env');
        $this->line("  Method: " . $method);

        if ($method === 'env') {
            $envKey = config('one-click-installer.installation_check.env_key', 'APP_INSTALLED');
            $envValue = env($envKey);
            $this->line("  Environment Key: " . $envKey);
            $this->line("  Environment Value: " . ($envValue !== null ? var_export($envValue, true) : 'NULL'));
            $this->line("  Raw Value Type: " . gettype($envValue));
            
            // Additional debugging
            $this->line("  String comparison (=== 'true'): " . ($envValue === 'true' ? 'YES' : 'NO'));
            $this->line("  Boolean comparison (=== true): " . ($envValue === true ? 'YES' : 'NO'));
            $this->line("  Loose comparison (== true): " . ($envValue == true ? 'YES' : 'NO'));
            
            // Check .env file directly
            $envPath = base_path('.env');
            if (file_exists($envPath)) {
                $envContent = file_get_contents($envPath);
                if (preg_match("/^{$envKey}=(.*)$/m", $envContent, $matches)) {
                    $fileValue = trim($matches[1]);
                    $this->line("  Value in .env file: '" . $fileValue . "'");
                    $this->line("  File value length: " . strlen($fileValue));
                    $this->line("  File value matches 'true': " . ($fileValue === 'true' ? 'YES' : 'NO'));
                } else {
                    $this->line("  Key not found in .env file");
                }
            } else {
                $this->line("  .env file not found");
            }
            
            // Test the checkByEnvironment method directly
            $envCheck = $installationService->checkByEnvironment();
            $this->line("  Environment Check Result: " . ($envCheck ? 'TRUE' : 'FALSE'));
        } elseif ($method === 'file') {
            $filePath = config('one-click-installer.installation_check.file_path', storage_path('app/installed'));
            $this->line("  File Path: " . $filePath);
            $this->line("  File Exists: " . (file_exists($filePath) ? 'YES' : 'NO'));
            
            if (file_exists($filePath)) {
                $content = file_get_contents($filePath);
                $this->line("  File Content: " . (strlen($content) > 100 ? substr($content, 0, 100) . '...' : $content));
            }
        } elseif ($method === 'database') {
            $table = config('one-click-installer.installation_check.database_table', 'settings');
            $key = config('one-click-installer.installation_check.database_key', 'app_installed');
            $this->line("  Database Table: " . $table);
            $this->line("  Database Key: " . $key);
            
            // Test database check
            $dbCheck = $installationService->checkByDatabase();
            $this->line("  Database Check Result: " . ($dbCheck ? 'TRUE' : 'FALSE'));
        }

        $this->line('');
        $this->line('Detailed Information:');
        $details = $info['installation_details'];
        foreach ($details as $key => $value) {
            if (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            } elseif (is_null($value)) {
                $value = 'NULL';
            }
            $this->line("  " . ucfirst(str_replace('_', ' ', $key)) . ": " . $value);
        }

        $this->line('');
        $this->line('Actions:');
        $this->line('  Reset Installation: php artisan installer:reset');
        $this->line('  Mark as Installed: php artisan installer:install');
        $this->line('  Check Status: php artisan installer:status');

        return 0;
    }
}