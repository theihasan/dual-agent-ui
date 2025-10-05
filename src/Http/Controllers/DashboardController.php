<?php

namespace Ihasan\DualAgentUI\Http\Controllers;

use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Dashboard', [
            'metrics' => $this->getMetrics(),
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

    public function requests(): Response
    {
        return Inertia::render('Requests', [
            'requests' => $this->getRequests(),
        ]);
    }

    public function exceptions(): Response
    {
        return Inertia::render('Exceptions', [
            'exceptions' => $this->getExceptions(),
        ]);
    }

    public function issues(): Response
    {
        return Inertia::render('Issues', [
            'issues' => $this->getIssues(),
        ]);
    }

    protected function getMetrics(): array
    {
        return [
            'total_requests' => 1234,
            'error_rate' => 2.5,
            'avg_response_time' => 145,
        ];
    }

    protected function getRequests(): array
    {
        return [
            // Mock data for now
        ];
    }

    protected function getExceptions(): array
    {
        return [
            // Mock data for now
        ];
    }

    protected function getIssues(): array
    {
        return [
            // Mock data for now - in real app this would fetch from database
        ];
    }
}
