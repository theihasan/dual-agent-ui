<?php

namespace Ihasan\DualAgentUI\Tests;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Ihasan\DualAgentUI\Commands\DualAgentUICommand;
use Ihasan\DualAgentUI\DualAgentUIServiceProvider;
use PHPUnit\Framework\Attributes\Test;

class DualAgentUIServiceProviderTest extends TestCase
{
    #[Test]
    public function it_registers_the_service_provider(): void
    {
        $this->assertTrue($this->app->getProviders(DualAgentUIServiceProvider::class) !== []);
    }

    #[Test]
    public function it_registers_the_command(): void
    {
        $this->assertTrue($this->app->make('Illuminate\Contracts\Console\Kernel')->all()['dual-agent-ui'] instanceof DualAgentUICommand);
    }

    #[Test]
    public function it_registers_routes(): void
    {
        $routes = Route::getRoutes();
        $agentDashboardRoute = $routes->getByName('dashboard');
        
        $this->assertNotNull($agentDashboardRoute);
        $this->assertEquals('GET', $agentDashboardRoute->methods()[0]);
        $this->assertEquals('agent-dashboard', $agentDashboardRoute->uri());
    }

    #[Test]
    public function it_loads_views(): void
    {
        $this->assertTrue($this->app['view']->exists('dual-agent-ui::app'));
    }

    #[Test]
    public function it_publishes_config(): void
    {
        $this->artisan('vendor:publish', [
            '--tag' => 'dual-agent-ui-config',
            '--force' => true,
        ]);

        $this->assertTrue(File::exists(config_path('dual-agent-ui.php')));

        // Clean up
        File::delete(config_path('dual-agent-ui.php'));
    }

    #[Test]
    public function it_publishes_views(): void
    {
        $this->artisan('vendor:publish', [
            '--tag' => 'dual-agent-ui-views',
            '--force' => true,
        ]);

        $this->assertTrue(File::exists(resource_path('views/vendor/dual-agent-ui')));

        // Clean up
        File::deleteDirectory(resource_path('views/vendor/dual-agent-ui'));
    }

    #[Test]
    public function it_publishes_migrations(): void
    {
        $this->artisan('vendor:publish', [
            '--tag' => 'dual-agent-ui-migrations',
            '--force' => true,
        ]);

        $migrationFiles = File::glob(database_path('migrations/*_create_dual_agent_ui_table.php'));
        $this->assertNotEmpty($migrationFiles);

        // Clean up
        foreach ($migrationFiles as $file) {
            File::delete($file);
        }
    }

    #[Test]
    public function config_file_has_expected_structure(): void
    {
        $config = include __DIR__ . '/../config/dual-agent-ui.php';
        
        $this->assertIsArray($config);
    }

    #[Test]
    public function app_blade_view_contains_inertia_setup(): void
    {
        $viewContent = File::get(__DIR__ . '/../resources/views/app.blade.php');
        
        $this->assertStringContainsString('@inertia', $viewContent);
        $this->assertStringContainsString('@inertiaHead', $viewContent);
        $this->assertStringContainsString('@vite', $viewContent);
        $this->assertStringContainsString('Dual Agent UI', $viewContent);
    }

    #[Test]
    public function dashboard_vue_component_exists_and_has_expected_structure(): void
    {
        $vueContent = File::get(__DIR__ . '/../resources/js/Pages/Dashboard.vue');
        
        $this->assertStringContainsString('<template>', $vueContent);
        $this->assertStringContainsString('<script setup>', $vueContent);
        $this->assertStringContainsString('defineProps', $vueContent);
        $this->assertStringContainsString('stats', $vueContent);
        $this->assertStringContainsString('recentActivities', $vueContent);
        $this->assertStringContainsString('Dashboard', $vueContent);
    }

    #[Test]
    public function inertia_middleware_stub_has_flash_messages(): void
    {
        $middlewareContent = File::get(__DIR__ . '/../stubs/HandleInertiaRequests.php');
        
        $this->assertStringContainsString('flash', $middlewareContent);
        $this->assertStringContainsString('success', $middlewareContent);
        $this->assertStringContainsString('error', $middlewareContent);
        $this->assertStringContainsString('warning', $middlewareContent);
        $this->assertStringContainsString('info', $middlewareContent);
        $this->assertStringContainsString('$request->session()->get', $middlewareContent);
    }

    #[Test]
    public function vite_config_stub_has_vue_support(): void
    {
        $viteContent = File::get(__DIR__ . '/../stubs/vite.config.js');
        
        $this->assertStringContainsString("import vue from '@vitejs/plugin-vue'", $viteContent);
        $this->assertStringContainsString('vue()', $viteContent);
        $this->assertStringContainsString('laravel-vite-plugin', $viteContent);
    }

    #[Test]
    public function app_js_stub_has_inertia_setup(): void
    {
        $appJsContent = File::get(__DIR__ . '/../stubs/app.js');
        
        $this->assertStringContainsString("import { createApp, h } from 'vue'", $appJsContent);
        $this->assertStringContainsString("import { createInertiaApp } from '@inertiajs/vue3'", $appJsContent);
        $this->assertStringContainsString('createInertiaApp', $appJsContent);
        $this->assertStringContainsString('./Pages/**/*.vue', $appJsContent);
    }

    #[Test]
    public function package_json_stub_has_required_dependencies(): void
    {
        $packageJsonContent = File::get(__DIR__ . '/../stubs/package.json.stub');
        $packageData = json_decode($packageJsonContent, true);
        
        $this->assertArrayHasKey('devDependencies', $packageData);
        $this->assertArrayHasKey('@inertiajs/vue3', $packageData['devDependencies']);
        $this->assertArrayHasKey('@vitejs/plugin-vue', $packageData['devDependencies']);
        $this->assertArrayHasKey('vue', $packageData['devDependencies']);
    }
}