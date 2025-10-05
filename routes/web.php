<?php

use Ihasan\DualAgentUI\Http\Controllers\DashboardController;
use Ihasan\DualAgentUI\Http\Middleware\HandleDualAgentInertiaRequests;
use Illuminate\Support\Facades\Route;

// Routes are automatically prefixed and middleware applied by ServiceProvider
Route::middleware([HandleDualAgentInertiaRequests::class])
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])
            ->name('dual-agent-ui.dashboard');

        Route::get('/requests', [DashboardController::class, 'requests'])
            ->name('dual-agent-ui.requests');

        Route::get('/exceptions', [DashboardController::class, 'exceptions'])
            ->name('dual-agent-ui.exceptions');
    });
