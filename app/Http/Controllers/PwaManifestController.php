<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\JsonResponse;

class PwaManifestController extends Controller
{
    /**
     * Web app manifest for the admin panel (installable PWA) using the same favicon as the public site.
     */
    public function admin(): JsonResponse
    {
        $settings = SiteSetting::getAllAsArray();
        $faviconPath = $settings['favicon'] ?? null;

        $iconUrl = ($faviconPath !== null && $faviconPath !== '')
            ? asset('storage/' . $faviconPath)
            : asset('main/assets/images/favicon.png');

        $iconType = is_string($faviconPath) && str_ends_with(strtolower($faviconPath), '.ico')
            ? 'image/x-icon'
            : 'image/png';

        $siteName = $settings['site_name'] ?? config('app.name', 'Wilchar LMS');
        $startUrl = url('/admin/dashboard');
        $scope = rtrim(url('/'), '/') . '/';

        $manifest = [
            'name' => $siteName . ' — Admin',
            'short_name' => 'Admin',
            'description' => 'Wilchar loan management admin panel. Install to open like a desktop app.',
            'start_url' => $startUrl,
            'scope' => $scope,
            'display' => 'standalone',
            'background_color' => '#f1f5f9',
            'theme_color' => '#10b981',
            'orientation' => 'any',
            'lang' => 'en',
            'dir' => 'ltr',
            'categories' => ['finance', 'business', 'productivity'],
            'icons' => [
                ['src' => $iconUrl, 'sizes' => '192x192', 'type' => $iconType, 'purpose' => 'any'],
                ['src' => $iconUrl, 'sizes' => '512x512', 'type' => $iconType, 'purpose' => 'any'],
                ['src' => $iconUrl, 'sizes' => '512x512', 'type' => $iconType, 'purpose' => 'maskable'],
            ],
            'shortcuts' => [
                [
                    'name' => 'Dashboard',
                    'short_name' => 'Dashboard',
                    'url' => url('/admin/dashboard'),
                    'icons' => [['src' => $iconUrl, 'sizes' => '96x96', 'type' => $iconType]],
                ],
                [
                    'name' => 'Loan applications',
                    'short_name' => 'Applications',
                    'url' => url('/admin/loan-applications'),
                    'icons' => [['src' => $iconUrl, 'sizes' => '96x96', 'type' => $iconType]],
                ],
            ],
        ];

        return response()
            ->json($manifest, 200, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
            ->header('Content-Type', 'application/manifest+json');
    }
}
