<?php

namespace Clevpro\LaravelOneClickInstaller\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Clevpro\LaravelOneClickInstaller\Services\InstallationService;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfInstalled
{
    protected InstallationService $installationService;

    public function __construct(InstallationService $installationService)
    {
        $this->installationService = $installationService;
    }

    /**
     * Handle an incoming request.
     * This middleware prevents access to installer routes when app is already installed
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->installationService->isInstalled()) {
            $redirectTo = config('one-click-installer.post_installation.redirect_to', '/');
            return redirect($redirectTo)->with('error', 'Application is already installed.');
        }

        return $next($request);
    }
}