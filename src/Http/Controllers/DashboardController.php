<?php

namespace Ihasan\DualAgentUI\Http\Controllers;

use Inertia\Inertia;

class DashboardController
{
    public function __invoke()
    {
        return Inertia::render('Dashboard', [
            'stats' => [
                'totalRequests' => 1234,
                'averageResponseTime' => 150,
                'errorRate' => 2.5,
                'uptime' => 99.9,
            ],
            'recentActivities' => [
                ['id' => 1, 'action' => 'Request processed', 'timestamp' => '2023-10-01 10:00:00'],
                ['id' => 2, 'action' => 'Error logged', 'timestamp' => '2023-10-01 09:45:00'],
                ['id' => 3, 'action' => 'Cache cleared', 'timestamp' => '2023-10-01 09:30:00'],
            ],
        ]);
    }
}