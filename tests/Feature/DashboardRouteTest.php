<?php

namespace Ihasan\DualAgentUI\Tests\Feature;

use Ihasan\DualAgentUI\Tests\TestCase;
use Inertia\Testing\AssertableInertia as Assert;
use PHPUnit\Framework\Attributes\Test;

class DashboardRouteTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Set up Inertia testing
        $this->app['config']->set('inertia.testing.ensure_pages_exist', false);
    }

    #[Test]
    public function dashboard_route_exists(): void
    {
        $response = $this->get('/agent-dashboard');

        $response->assertStatus(200);
    }

    #[Test]
    public function dashboard_route_returns_inertia_response(): void
    {
        $response = $this->get('/agent-dashboard');

        $response->assertInertia(fn (Assert $page) => $page
            ->component('Dashboard')
        );
    }

    #[Test]
    public function dashboard_route_provides_stats_data(): void
    {
        $response = $this->get('/agent-dashboard');

        $response->assertInertia(fn (Assert $page) => $page
            ->component('Dashboard')
            ->has('stats')
            ->has('stats.totalRequests')
            ->has('stats.averageResponseTime')
            ->has('stats.errorRate')
            ->has('stats.uptime')
            ->where('stats.totalRequests', 1234)
            ->where('stats.averageResponseTime', 150)
            ->where('stats.errorRate', 2.5)
            ->where('stats.uptime', 99.9)
        );
    }

    #[Test]
    public function dashboard_route_provides_recent_activities(): void
    {
        $response = $this->get('/agent-dashboard');

        $response->assertInertia(fn (Assert $page) => $page
            ->component('Dashboard')
            ->has('recentActivities')
            ->has('recentActivities', 3)
            ->has('recentActivities.0', fn (Assert $activity) => $activity
                ->where('id', 1)
                ->where('action', 'Request processed')
                ->where('timestamp', '2023-10-01 10:00:00')
            )
            ->has('recentActivities.1', fn (Assert $activity) => $activity
                ->where('id', 2)
                ->where('action', 'Error logged')
                ->where('timestamp', '2023-10-01 09:45:00')
            )
            ->has('recentActivities.2', fn (Assert $activity) => $activity
                ->where('id', 3)
                ->where('action', 'Cache cleared')
                ->where('timestamp', '2023-10-01 09:30:00')
            )
        );
    }

    #[Test]
    public function dashboard_route_has_correct_name(): void
    {
        $this->assertTrue(route('dashboard') !== null);
        $this->assertEquals(url('/agent-dashboard'), route('dashboard'));
    }

    #[Test]
    public function dashboard_route_uses_get_method(): void
    {
        $routes = app('router')->getRoutes();
        $dashboardRoute = $routes->getByName('dashboard');

        $this->assertContains('GET', $dashboardRoute->methods());
        $this->assertNotContains('POST', $dashboardRoute->methods());
        $this->assertNotContains('PUT', $dashboardRoute->methods());
        $this->assertNotContains('DELETE', $dashboardRoute->methods());
    }

    #[Test]
    public function dashboard_route_responds_to_json_requests(): void
    {
        $response = $this->getJson('/agent-dashboard');

        // Should return 200 and have Inertia structure
        $response->assertStatus(200);
        $response->assertHeader('X-Inertia', 'true');
    }

    #[Test]
    public function dashboard_route_has_correct_controller(): void
    {
        $routes = app('router')->getRoutes();
        $dashboardRoute = $routes->getByName('dashboard');

        $action = $dashboardRoute->getAction();
        $this->assertEquals('Ihasan\DualAgentUI\Http\Controllers\DashboardController', $action['controller']);
    }

    #[Test]
    public function dashboard_route_can_be_accessed_multiple_times(): void
    {
        // First request
        $response1 = $this->get('/agent-dashboard');
        $response1->assertStatus(200);

        // Second request
        $response2 = $this->get('/agent-dashboard');
        $response2->assertStatus(200);

        // Both should return the same data
        $response1->assertInertia(fn (Assert $page) => $page
            ->component('Dashboard')
            ->where('stats.totalRequests', 1234)
        );

        $response2->assertInertia(fn (Assert $page) => $page
            ->component('Dashboard')
            ->where('stats.totalRequests', 1234)
        );
    }

    #[Test]
    public function dashboard_route_includes_correct_inertia_headers(): void
    {
        $response = $this->get('/agent-dashboard', [
            'X-Inertia' => 'true',
            'X-Inertia-Version' => '1.0',
        ]);

        $response->assertStatus(200);
        $response->assertHeader('X-Inertia', 'true');
    }
}
