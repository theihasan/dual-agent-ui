<?php

namespace Ihasan\DualAgentUI\Tests\Unit;

use Ihasan\DualAgentUI\Http\Controllers\DashboardController;
use Ihasan\DualAgentUI\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class SimpleIntegrationTest extends TestCase
{
    #[Test]
    public function controller_can_be_instantiated(): void
    {
        $controller = new DashboardController();
        
        $this->assertInstanceOf(DashboardController::class, $controller);
    }

    #[Test]
    public function controller_returns_inertia_response(): void
    {
        $controller = new DashboardController();
        $response = $controller();
        
        $this->assertInstanceOf(\Inertia\Response::class, $response);
    }

    #[Test]
    public function route_is_registered(): void
    {
        $router = $this->app['router'];
        $routes = $router->getRoutes();
        
        $dashboardRoute = $routes->getByName('dashboard');
        $this->assertNotNull($dashboardRoute);
        $this->assertEquals('agent-dashboard', $dashboardRoute->uri());
    }

    #[Test]
    public function service_provider_is_loaded(): void
    {
        $providers = $this->app->getLoadedProviders();
        
        $this->assertArrayHasKey('Ihasan\DualAgentUI\DualAgentUIServiceProvider', $providers);
    }

    #[Test]
    public function config_is_publishable(): void
    {
        $configPath = __DIR__ . '/../../config/dual-agent-ui.php';
        
        $this->assertFileExists($configPath);
        
        $config = include $configPath;
        $this->assertIsArray($config);
    }

    #[Test]
    public function views_are_loadable(): void
    {
        $viewPath = __DIR__ . '/../../resources/views/app.blade.php';
        
        $this->assertFileExists($viewPath);
        
        $content = file_get_contents($viewPath);
        $this->assertStringContainsString('@inertia', $content);
        $this->assertStringContainsString('@inertiaHead', $content);
    }

    #[Test]
    public function dashboard_component_exists(): void
    {
        $componentPath = __DIR__ . '/../../resources/js/Pages/Dashboard.vue';
        
        $this->assertFileExists($componentPath);
        
        $content = file_get_contents($componentPath);
        $this->assertStringContainsString('<template>', $content);
        $this->assertStringContainsString('defineProps', $content);
    }

    #[Test]
    public function installation_stubs_exist(): void
    {
        $stubsPath = __DIR__ . '/../../stubs/';
        
        $this->assertFileExists($stubsPath . 'HandleInertiaRequests.php');
        $this->assertFileExists($stubsPath . 'app.js');
        $this->assertFileExists($stubsPath . 'vite.config.js');
        $this->assertFileExists($stubsPath . 'package.json.stub');
    }

    #[Test]
    public function migration_stub_exists(): void
    {
        $migrationPath = __DIR__ . '/../../database/migrations/create_dual_agent_ui_table.php.stub';
        
        $this->assertFileExists($migrationPath);
    }

    #[Test]
    public function package_structure_is_valid(): void
    {
        $basePath = __DIR__ . '/../..';
        
        // Essential package files
        $this->assertFileExists($basePath . '/composer.json');
        $this->assertFileExists($basePath . '/src/DualAgentUIServiceProvider.php');
        $this->assertFileExists($basePath . '/routes/dual-agent-ui.php');
        
        // Test structure
        $this->assertDirectoryExists($basePath . '/tests');
        $this->assertFileExists($basePath . '/phpunit.xml.dist');
    }
}