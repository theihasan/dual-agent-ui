<?php

use Illuminate\Support\Facades\Route;
use Ihasan\DualAgentUI\Http\Controllers\DashboardController;

Route::get('/agent-dashboard', DashboardController::class)->name('dashboard');