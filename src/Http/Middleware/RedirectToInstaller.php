<?php

namespace Clevpro\LaravelOneClickInstaller\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Clevpro\LaravelOneClickInstaller\Services\InstallationService;
use Symfony\Component\HttpFoundation\Response;

class RedirectToInstaller
{
    protected InstallationService $installationService;

    public function __construct(InstallationService $installationService)
    {
        $this->installationService = $installationService;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip for AJAX requests and API routes
        if ($request->wantsJson() || $request->is('api/*')) {
            return $next($request);
        }

        // Skip for installer routes themselves
        if ($this->installationService->isInstallerRoute()) {
            return $next($request);
        }

        // Skip for asset requests
        if ($this->isAssetRequest($request)) {
            return $next($request);
        }

        // Check if we should redirect to installer
        if ($this->installationService->shouldRedirectToInstaller()) {
            return redirect($this->installationService->getInstallerUrl());
        }

        return $next($request);
    }

    /**
     * Check if the request is for static assets
     */
    private function isAssetRequest(Request $request): bool
    {
        $path = $request->path();
        
        $assetPatterns = [
            'css/',
            'js/',
            'images/',
            'img/',
            'fonts/',
            'assets/',
            'vendor/',
            'storage/',
            'favicon.ico',
            'robots.txt',
            'sitemap.xml',
        ];

        foreach ($assetPatterns as $pattern) {
            if (str_starts_with($path, $pattern) || str_ends_with($path, $pattern)) {
                return true;
            }
        }

        // Check for common file extensions
        $fileExtensions = [
            '.css', '.js', '.png', '.jpg', '.jpeg', '.gif', '.svg', '.woff', '.woff2', 
            '.ttf', '.eot', '.ico', '.webp', '.pdf', '.zip', '.json', '.xml'
        ];

        foreach ($fileExtensions as $extension) {
            if (str_ends_with($path, $extension)) {
                return true;
            }
        }

        return false;
    }
}