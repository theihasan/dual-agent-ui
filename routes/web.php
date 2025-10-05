<?php

use Illuminate\Support\Facades\Route;
use Ihasan\DualAgentUI\Http\Controllers\DashboardController;
use Ihasan\DualAgentUI\Http\Controllers\ExceptionController;
use Ihasan\DualAgentUI\Http\Controllers\IssueController;
use Ihasan\DualAgentUI\Http\Middleware\HandleDualAgentInertiaRequests;

// Routes are automatically prefixed and middleware applied by ServiceProvider
Route::middleware([HandleDualAgentInertiaRequests::class])
    ->group(function () {
        // Dashboard routes
        Route::get('/', [DashboardController::class, 'index'])
            ->name('dual-agent-ui.dashboard');
        
        Route::get('/requests', [DashboardController::class, 'requests'])
            ->name('dual-agent-ui.requests');

        // Exception routes
        Route::prefix('exceptions')->name('dual-agent-ui.exceptions.')->group(function () {
            Route::get('/', [ExceptionController::class, 'index'])
                ->name('index');
            Route::get('/data', [ExceptionController::class, 'data'])
                ->name('data');
            Route::get('/{id}', [ExceptionController::class, 'show'])
                ->name('show');
            Route::patch('/{id}/status', [ExceptionController::class, 'updateStatus'])
                ->name('update-status');
            Route::post('/bulk-action', [ExceptionController::class, 'bulkAction'])
                ->name('bulk-action');
        });

        // Issue routes
        Route::prefix('issues')->name('dual-agent-ui.issues.')->group(function () {
            Route::get('/', [IssueController::class, 'index'])
                ->name('index');
            Route::get('/data', [IssueController::class, 'data'])
                ->name('data');
            Route::post('/', [IssueController::class, 'store'])
                ->name('store');
            Route::get('/{id}', [IssueController::class, 'show'])
                ->name('show');
            Route::patch('/{id}', [IssueController::class, 'update'])
                ->name('update');
            Route::delete('/{id}', [IssueController::class, 'destroy'])
                ->name('destroy');
            Route::post('/bulk-action', [IssueController::class, 'bulkAction'])
                ->name('bulk-action');
        });

        // Legacy route redirects for backward compatibility
        Route::get('/exceptions', function () {
            return redirect()->route('dual-agent-ui.exceptions.index');
        });
        
        Route::get('/issues', function () {
            return redirect()->route('dual-agent-ui.issues.index');
        });
    });