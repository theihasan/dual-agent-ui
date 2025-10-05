<?php

namespace Ihasan\DualAgentUI\Tests;

use Ihasan\DualAgentUI\Http\Controllers\DashboardController;
use Inertia\Testing\AssertableInertia as Assert;
use PHPUnit\Framework\Attributes\Test;

class DashboardControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Set up Inertia testing
        $this->app['config']->set('inertia.testing.ensure_pages_exist', false);
    }

    #[Test]
    public function it_returns_inertia_response(): void
    {
        $controller = new DashboardController;
        $response = $controller();

        $this->assertInstanceOf(\Inertia\Response::class, $response);
    }

    #[Test]
    public function it_renders_dashboard_component(): void
    {
        $controller = new DashboardController;
        $response = $controller();

        // Use reflection to access protected properties
        $reflection = new \ReflectionClass($response);
        $componentProperty = $reflection->getProperty('component');
        $componentProperty->setAccessible(true);

        $this->assertEquals('Dashboard', $componentProperty->getValue($response));
    }

    #[Test]
    public function it_provides_stats_data(): void
    {
        $controller = new DashboardController;
        $response = $controller();

        // Use reflection to access protected properties
        $reflection = new \ReflectionClass($response);
        $propsProperty = $reflection->getProperty('props');
        $propsProperty->setAccessible(true);
        $props = $propsProperty->getValue($response);

        $this->assertArrayHasKey('stats', $props);

        $stats = $props['stats'];
        $this->assertArrayHasKey('totalRequests', $stats);
        $this->assertArrayHasKey('averageResponseTime', $stats);
        $this->assertArrayHasKey('errorRate', $stats);
        $this->assertArrayHasKey('uptime', $stats);

        // Assert specific values
        $this->assertEquals(1234, $stats['totalRequests']);
        $this->assertEquals(150, $stats['averageResponseTime']);
        $this->assertEquals(2.5, $stats['errorRate']);
        $this->assertEquals(99.9, $stats['uptime']);
    }

    #[Test]
    public function it_provides_recent_activities_data(): void
    {
        $controller = new DashboardController;
        $response = $controller();

        // Use reflection to access protected properties
        $reflection = new \ReflectionClass($response);
        $propsProperty = $reflection->getProperty('props');
        $propsProperty->setAccessible(true);
        $props = $propsProperty->getValue($response);

        $this->assertArrayHasKey('recentActivities', $props);

        $activities = $props['recentActivities'];
        $this->assertIsArray($activities);
        $this->assertCount(3, $activities);

        // Check first activity structure
        $firstActivity = $activities[0];
        $this->assertArrayHasKey('id', $firstActivity);
        $this->assertArrayHasKey('action', $firstActivity);
        $this->assertArrayHasKey('timestamp', $firstActivity);

        // Assert specific values
        $this->assertEquals(1, $firstActivity['id']);
        $this->assertEquals('Request processed', $firstActivity['action']);
        $this->assertEquals('2023-10-01 10:00:00', $firstActivity['timestamp']);
    }

    #[Test]
    public function it_has_correct_data_types(): void
    {
        $controller = new DashboardController;
        $response = $controller();

        // Use reflection to access protected properties
        $reflection = new \ReflectionClass($response);
        $propsProperty = $reflection->getProperty('props');
        $propsProperty->setAccessible(true);
        $props = $propsProperty->getValue($response);

        $stats = $props['stats'];

        $this->assertIsInt($stats['totalRequests']);
        $this->assertIsInt($stats['averageResponseTime']);
        $this->assertIsFloat($stats['errorRate']);
        $this->assertIsFloat($stats['uptime']);

        $activities = $props['recentActivities'];
        foreach ($activities as $activity) {
            $this->assertIsInt($activity['id']);
            $this->assertIsString($activity['action']);
            $this->assertIsString($activity['timestamp']);
        }
    }

    #[Test]
    public function stats_contain_reasonable_values(): void
    {
        $controller = new DashboardController;
        $response = $controller();

        // Use reflection to access protected properties
        $reflection = new \ReflectionClass($response);
        $propsProperty = $reflection->getProperty('props');
        $propsProperty->setAccessible(true);
        $props = $propsProperty->getValue($response);

        $stats = $props['stats'];

        // Assert reasonable ranges
        $this->assertGreaterThan(0, $stats['totalRequests']);
        $this->assertGreaterThan(0, $stats['averageResponseTime']);
        $this->assertGreaterThanOrEqual(0, $stats['errorRate']);
        $this->assertLessThanOrEqual(100, $stats['errorRate']);
        $this->assertGreaterThanOrEqual(0, $stats['uptime']);
        $this->assertLessThanOrEqual(100, $stats['uptime']);
    }

    #[Test]
    public function activities_have_valid_timestamps(): void
    {
        $controller = new DashboardController;
        $response = $controller();

        // Use reflection to access protected properties
        $reflection = new \ReflectionClass($response);
        $propsProperty = $reflection->getProperty('props');
        $propsProperty->setAccessible(true);
        $props = $propsProperty->getValue($response);

        $activities = $props['recentActivities'];

        foreach ($activities as $activity) {
            $timestamp = $activity['timestamp'];
            $this->assertMatchesRegularExpression(
                '/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/',
                $timestamp,
                "Timestamp '{$timestamp}' does not match expected format"
            );

            // Verify it's a valid date
            $date = \DateTime::createFromFormat('Y-m-d H:i:s', $timestamp);
            $this->assertInstanceOf(\DateTime::class, $date);
        }
    }

    #[Test]
    public function activities_are_ordered_by_id(): void
    {
        $controller = new DashboardController;
        $response = $controller();

        // Use reflection to access protected properties
        $reflection = new \ReflectionClass($response);
        $propsProperty = $reflection->getProperty('props');
        $propsProperty->setAccessible(true);
        $props = $propsProperty->getValue($response);

        $activities = $props['recentActivities'];

        $previousId = 0;
        foreach ($activities as $activity) {
            $this->assertGreaterThan($previousId, $activity['id']);
            $previousId = $activity['id'];
        }
    }

    #[Test]
    public function controller_is_invokable(): void
    {
        $controller = new DashboardController;

        $this->assertTrue(method_exists($controller, '__invoke'));
        $this->assertTrue(is_callable($controller));
    }
}
