<?php

namespace Clevpro\LaravelOneClickInstaller\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class AssetController extends Controller
{
    /**
     * Serve CSS assets directly from package
     */
    public function css(Request $request, string $filename)
    {
        $path = __DIR__ . '/../../../resources/assets/' . $filename;
        
        if (!file_exists($path) || !str_ends_with($filename, '.css')) {
            abort(404);
        }
        
        $content = file_get_contents($path);
        
        return response($content, 200, [
            'Content-Type' => 'text/css',
            'Cache-Control' => 'public, max-age=31536000', // 1 year cache
            'Expires' => gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT',
        ]);
    }
    
    /**
     * Serve JavaScript assets directly from package
     */
    public function js(Request $request, string $filename)
    {
        $path = __DIR__ . '/../../../resources/assets/' . $filename;
        
        if (!file_exists($path) || !str_ends_with($filename, '.js')) {
            abort(404);
        }
        
        $content = file_get_contents($path);
        
        return response($content, 200, [
            'Content-Type' => 'application/javascript',
            'Cache-Control' => 'public, max-age=31536000', // 1 year cache
            'Expires' => gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT',
        ]);
    }
}