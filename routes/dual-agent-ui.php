<?php

use Illuminate\Support\Facades\Route;
use Ihasan\DualAgentUI\Http\Controllers\DashboardController;

Route::get('/dashboard', DashboardController::class)->name('dashboard');