<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Dual Agent UI Path
    |--------------------------------------------------------------------------
    |
    | This is the URI path where Dual Agent UI will be accessible from.
    |
    */
    'path' => env('DUAL_AGENT_UI_PATH', 'agent-dashboard'),

    /*
    |--------------------------------------------------------------------------
    | Dual Agent UI Route Middleware
    |--------------------------------------------------------------------------
    |
    | These middleware will be assigned to every Dual Agent UI route, giving
    | you the chance to add your own middleware to this list or change any
    | of the existing middleware. Note: Spatie Package Tools will automatically
    | apply these middleware to routes defined in routes/web.php
    |
    */
    'middleware' => ['web', 'auth'],

    /*
    |--------------------------------------------------------------------------
    | Authorization Gate
    |--------------------------------------------------------------------------
    |
    | Define who can access the Dual Agent UI dashboard. By default, all
    | authenticated users can access it. Customize this in your AppServiceProvider.
    |
    | Example: Gate::define('viewDualAgentUI', fn($user) => $user->isAdmin());
    |
    */
    'gate' => env('DUAL_AGENT_UI_GATE'),

    /*
    |--------------------------------------------------------------------------
    | Asset Path
    |--------------------------------------------------------------------------
    |
    | The path where pre-built assets are published.
    |
    */
    'asset_path' => 'vendor/dual-agent-ui/build',
];
