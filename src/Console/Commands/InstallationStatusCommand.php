<?php

namespace Clevpro\LaravelOneClickInstaller\Console\Commands;

use Illuminate\Console\Command;
use Clevpro\LaravelOneClickInstaller\Services\InstallationService;

class InstallationStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'installer:status 
                            {--reset : Reset the installation status}
                            {--mark-installed : Mark the application as installed}';

    /**
     * The console command description.
     */
    protected $description = 'Check or manage the application installation status';

    protected InstallationService $installationService;

    public function __construct(InstallationService $installationService)
    {
        parent::__construct();
        $this->installationService = $installationService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // Handle reset option
        if ($this->option('reset')) {
            return $this->resetInstallation();
        }

        // Handle mark-installed option
        if ($this->option('mark-installed')) {
            return $this->markAsInstalled();
        }

        // Show installation status
        return $this->showStatus();
    }

    /**
     * Show the current installation status
     */
    private function showStatus(): int
    {
        $info = $this->installationService->getInstallationInfo();

        $this->info('🔍 Installation Status Report');
        $this->line('');

        // Installation status
        if ($info['is_installed']) {
            $this->info('✅ Application is INSTALLED');
        } else {
            $this->warn('❌ Application is NOT INSTALLED');
        }

        $this->line('');

        // Configuration details
        $this->comment('📋 Configuration:');
        $this->line("   Check Method: {$info['check_method']}");
        $this->line("   Installer URL: {$info['installer_url']}");
        $this->line("   Current Route: /{$info['current_route']}");

        if ($info['should_redirect']) {
            $this->warn("   ⚠️  Will redirect to installer");
        } else {
            $this->info("   ✅ No redirect needed");
        }

        $this->line('');

        // Method-specific details
        $this->comment('🔧 Method Details:');
        foreach ($info['installation_details'] as $key => $value) {
            $status = is_bool($value) ? ($value ? '✅' : '❌') : $value;
            $this->line("   " . ucwords(str_replace('_', ' ', $key)) . ": {$status}");
        }

        $this->line('');

        // Show available commands
        if (!$info['is_installed']) {
            $this->comment('💡 Available Commands:');
            $this->line('   php artisan installer:status --mark-installed  # Mark as installed');
            $this->line("   Visit: {$info['installer_url']}              # Run installer wizard");
        } else {
            $this->comment('💡 Available Commands:');
            $this->line('   php artisan installer:status --reset         # Reset installation status');
        }

        return Command::SUCCESS;
    }

    /**
     * Reset the installation status
     */
    private function resetInstallation(): int
    {
        $this->warn('🔄 Resetting installation status...');

        if ($this->installationService->resetInstallation()) {
            $this->info('✅ Installation status has been reset');
            $this->line('');
            $this->comment('The application will now redirect to the installer wizard.');
            $this->line("Installer URL: {$this->installationService->getInstallerUrl()}");
        } else {
            $this->error('❌ Failed to reset installation status');
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    /**
     * Mark the application as installed
     */
    private function markAsInstalled(): int
    {
        if ($this->installationService->isInstalled()) {
            $this->info('ℹ️  Application is already marked as installed');
            return Command::SUCCESS;
        }

        $this->info('🚀 Marking application as installed...');

        if ($this->installationService->markAsInstalled()) {
            $this->info('✅ Application has been marked as installed');
            $this->line('');
            $this->comment('The installer wizard will no longer be accessible.');
        } else {
            $this->error('❌ Failed to mark application as installed');
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}