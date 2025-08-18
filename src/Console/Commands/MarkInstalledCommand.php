<?php

namespace Clevpro\LaravelOneClickInstaller\Console\Commands;

use Illuminate\Console\Command;
use Clevpro\LaravelOneClickInstaller\Services\InstallationService;

class MarkInstalledCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'installer:install';

    /**
     * The console command description.
     */
    protected $description = 'Mark the application as installed (for testing)';

    /**
     * Execute the console command.
     */
    public function handle(InstallationService $installationService): int
    {
        $this->info('Marking application as installed...');

        $success = $installationService->markAsInstalled();

        if ($success) {
            $this->info('✅ Application marked as installed successfully!');
            
            // Show verification
            $this->line('');
            $this->line('Verification:');
            $isInstalled = $installationService->isInstalled();
            $this->line('Installation Status: ' . ($isInstalled ? 'INSTALLED' : 'NOT INSTALLED'));
            
            if ($isInstalled) {
                $this->info('✅ Verification successful!');
            } else {
                $this->error('❌ Verification failed! Application is still marked as not installed.');
            }
        } else {
            $this->error('❌ Failed to mark application as installed!');
            return 1;
        }

        return 0;
    }
}