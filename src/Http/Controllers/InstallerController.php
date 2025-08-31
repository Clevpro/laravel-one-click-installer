<?php

namespace Clevpro\LaravelOneClickInstaller\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Clevpro\LaravelOneClickInstaller\Services\InstallationService;
use Exception;

class InstallerController extends Controller
{
    protected InstallationService $installationService;

    public function __construct(InstallationService $installationService)
    {
        $this->installationService = $installationService;
    }
    /**
     * Step 1: Welcome screen
     */
    public function welcome(): View
    {
        return view('installer::welcome', [
            'appName' => config('one-click-installer.ui.app_name'),
            'currentStep' => 1,
            'totalSteps' => 5,
        ]);
    }

    /**
     * Step 2: Environment setup form
     */
    public function environment(): View
    {
        return view('installer::environment', [
            'appName' => config('one-click-installer.ui.app_name'),
            'currentStep' => 2,
            'totalSteps' => 5,
            'envValues' => $this->getCurrentEnvValues(),
        ]);
    }

    /**
     * Process environment setup
     */
    public function storeEnvironment(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'app_name' => 'required|string|max:255',
            'app_url' => 'required|url',
            'db_host' => 'required|string',
            'db_port' => 'required|integer|min:1|max:65535',
            'db_database' => 'required|string',
            'db_username' => 'required|string',
            'db_password' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Test database connection
            $this->testDatabaseConnection($request->only([
                'db_host', 'db_port', 'db_database', 'db_username', 'db_password'
            ]));

            // Update .env file
            $this->updateEnvFile([
                'APP_NAME' => $request->app_name,
                'APP_URL' => $request->app_url,
                'DB_HOST' => $request->db_host,
                'DB_PORT' => $request->db_port,
                'DB_DATABASE' => $request->db_database,
                'DB_USERNAME' => $request->db_username,
                'DB_PASSWORD' => $request->db_password,
            ]);

            // Store environment data in session for next steps
            session([
                'installer_env' => $request->only([
                    'app_name', 'app_url', 'db_host', 'db_port', 
                    'db_database', 'db_username', 'db_password'
                ])
            ]);

            return redirect()->route('installer.migrations');

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['database' => 'Database connection failed: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Step 3: Run migrations and seeders
     */
    public function migrations(): View
    {
        return view('installer::migrations', [
            'appName' => config('one-click-installer.ui.app_name'),
            'currentStep' => 3,
            'totalSteps' => 5,
        ]);
    }

    /**
     * Process migrations and seeders
     */
    public function runMigrations(): RedirectResponse
    {
        try {
            // Generate application key if not present
            if (config('one-click-installer.post_installation.generate_app_key')) {
                if (empty(config('app.key'))) {
                    Artisan::call('key:generate', ['--force' => true]);
                }
            }

            // Run migrations
            if (config('one-click-installer.post_installation.run_migrations')) {
                Artisan::call('migrate', ['--force' => true]);
            }

            // Run seeders
            if (config('one-click-installer.post_installation.run_seeders')) {
                Artisan::call('db:seed', ['--force' => true]);
            }

            // Create storage link
            if (config('one-click-installer.post_installation.create_storage_link')) {
                Artisan::call('storage:link');
            }

            // Clear cache
            if (config('one-click-installer.post_installation.clear_cache')) {
                Artisan::call('config:clear');
                Artisan::call('cache:clear');
                Artisan::call('view:clear');
            }

            session(['installer_migrations_done' => true]);

            return redirect()->route('installer.admin');

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['migration' => 'Migration failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Step 4: Admin account creation
     */
    public function admin(): View
    {
        return view('installer::admin', [
            'appName' => config('one-click-installer.ui.app_name'),
            'currentStep' => 4,
            'totalSteps' => 5,
        ]);
    }

    /**
     * Process admin account creation
     */
    public function storeAdmin(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $userModel = config('one-click-installer.admin_user.model');
            $admin = $userModel::updateOrCreate([
                'id' => 1,
            ], [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'email_verified_at' => config('one-click-installer.admin_user.email_verification') 
                    ? null 
                    : now(),
            ]);

            // Assign admin role if using Spatie Permission or similar
            if (method_exists($admin, 'assignRole')) {
                $defaultRole = config('one-click-installer.admin_user.default_role');
                if ($defaultRole) {
                    $admin->assignRole($defaultRole);
                }
            }

            session(['installer_admin_created' => true]);

            return redirect()->route('installer.finish');

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['admin' => 'Admin creation failed: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Step 5: Finish screen
     */
    public function finish(): View
    {
        // Mark installation as complete
        $this->markInstallationComplete();

        return view('installer::finish', [
            'appName' => config('one-click-installer.ui.app_name'),
            'currentStep' => 5,
            'totalSteps' => 5,
            'redirectUrl' => config('one-click-installer.post_installation.redirect_to', '/'),
        ]);
    }

    /**
     * Test database connection
     */
    private function testDatabaseConnection(array $config): void
    {
        $connection = config('database.default');
        
        Config::set("database.connections.{$connection}.host", $config['db_host']);
        Config::set("database.connections.{$connection}.port", $config['db_port']);
        Config::set("database.connections.{$connection}.database", $config['db_database']);
        Config::set("database.connections.{$connection}.username", $config['db_username']);
        Config::set("database.connections.{$connection}.password", $config['db_password']);

        DB::purge($connection);
        DB::reconnect($connection);
        DB::connection()->getPdo();
    }

    /**
     * Update .env file
     */
    private function updateEnvFile(array $data): void
    {
        $envFile = base_path('.env');
        
        if (!file_exists($envFile)) {
            // Create .env from .env.example if it doesn't exist
            if (file_exists(base_path('.env.example'))) {
                copy(base_path('.env.example'), $envFile);
            } else {
                file_put_contents($envFile, '');
            }
        }

        $envContent = file_get_contents($envFile);

        foreach ($data as $key => $value) {
            $value = is_string($value) && str_contains($value, ' ') ? '"' . $value . '"' : $value;
            
            if (str_contains($envContent, $key . '=')) {
                $envContent = preg_replace(
                    '/^' . preg_quote($key) . '=.*$/m',
                    $key . '=' . $value,
                    $envContent
                );
            } else {
                $envContent .= "\n" . $key . '=' . $value;
            }
        }

        file_put_contents($envFile, $envContent);
    }

    /**
     * Get current .env values
     */
    private function getCurrentEnvValues(): array
    {
        return [
            'app_name' => env('APP_NAME', 'Laravel Application'),
            'app_url' => env('APP_URL', 'http://localhost'),
            'db_host' => env('DB_HOST', 'localhost'),
            'db_port' => env('DB_PORT', '3306'),
            'db_database' => env('DB_DATABASE', ''),
            'db_username' => env('DB_USERNAME', ''),
            'db_password' => env('DB_PASSWORD', ''),
        ];
    }

    /**
     * Mark installation as complete
     */
    private function markInstallationComplete(): void
    {
        // Use the InstallationService to mark as installed
        $this->installationService->markAsInstalled();

        // Clear all installer session data
        session()->forget(['installer_env', 'installer_migrations_done', 'installer_admin_created']);
    }
}
