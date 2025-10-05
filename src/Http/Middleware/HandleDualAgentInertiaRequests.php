<?php

namespace Ihasan\DualAgentUI\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleDualAgentInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     */
    protected $rootView = 'dual-agent-ui::root';

    /**
     * Determines the current asset version.
     */
    public function version(Request $request): ?string
    {
        $cssPath = public_path('vendor/dual-agent-ui/build/app.css');
        $jsPath = public_path('vendor/dual-agent-ui/build/app.js');

        if (file_exists($cssPath) && file_exists($jsPath)) {
            return md5_file($cssPath).md5_file($jsPath);
        }

        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
                'warning' => fn () => $request->session()->get('warning'),
                'info' => fn () => $request->session()->get('info'),
            ],
        ]);
    }
}
