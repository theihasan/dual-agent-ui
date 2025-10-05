<?php

namespace Ihasan\DualAgentUI\Tests\Unit;

use Ihasan\DualAgentUI\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class InstallationTest extends TestCase
{
    #[Test]
    public function install_command_exists(): void
    {
        $this->assertTrue($this->app->make('Illuminate\Contracts\Console\Kernel')->all()['dual-agent-ui:install'] !== null);
    }

    #[Test]
    public function all_stub_files_exist(): void
    {
        $stubsPath = __DIR__.'/../../stubs/';

        $this->assertFileExists($stubsPath.'HandleInertiaRequests.php');
        $this->assertFileExists($stubsPath.'app.js');
        $this->assertFileExists($stubsPath.'vite.config.js');
        $this->assertFileExists($stubsPath.'package.json.stub');
    }

    #[Test]
    public function stub_files_have_valid_syntax(): void
    {
        // Test PHP syntax
        $phpStubs = [
            __DIR__.'/../../stubs/HandleInertiaRequests.php',
        ];

        foreach ($phpStubs as $stub) {
            $output = shell_exec("php -l {$stub} 2>&1");
            $this->assertStringContainsString('No syntax errors detected', $output);
        }

        // Test JavaScript syntax (basic check)
        $jsStubs = [
            __DIR__.'/../../stubs/app.js',
            __DIR__.'/../../stubs/vite.config.js',
        ];

        foreach ($jsStubs as $stub) {
            $content = file_get_contents($stub);
            // Basic checks for JavaScript syntax
            $this->assertStringNotContainsString('<?php', $content);
            $this->assertStringContainsString('import', $content);
        }

        // Test JSON syntax
        $jsonStubs = [
            __DIR__.'/../../stubs/package.json.stub',
        ];

        foreach ($jsonStubs as $stub) {
            $content = file_get_contents($stub);
            $decoded = json_decode($content, true);
            $this->assertNotNull($decoded, "Invalid JSON in {$stub}");
            $this->assertEquals(JSON_ERROR_NONE, json_last_error());
        }
    }

    #[Test]
    public function package_has_required_composer_configuration(): void
    {
        $composerPath = __DIR__.'/../../composer.json';
        $this->assertFileExists($composerPath);

        $composer = json_decode(file_get_contents($composerPath), true);

        $this->assertArrayHasKey('name', $composer);
        $this->assertArrayHasKey('autoload', $composer);
        $this->assertArrayHasKey('autoload-dev', $composer);
        $this->assertArrayHasKey('extra', $composer);
        $this->assertArrayHasKey('laravel', $composer['extra']);
        $this->assertArrayHasKey('providers', $composer['extra']['laravel']);

        // Check service provider is registered
        $this->assertContains(
            'Ihasan\\DualAgentUI\\DualAgentUIServiceProvider',
            $composer['extra']['laravel']['providers']
        );
    }

    #[Test]
    public function package_has_required_directories(): void
    {
        $basePath = __DIR__.'/../..';

        $this->assertDirectoryExists($basePath.'/src');
        $this->assertDirectoryExists($basePath.'/tests');
        $this->assertDirectoryExists($basePath.'/config');
        $this->assertDirectoryExists($basePath.'/resources');
        $this->assertDirectoryExists($basePath.'/resources/views');
        $this->assertDirectoryExists($basePath.'/resources/js');
        $this->assertDirectoryExists($basePath.'/resources/js/Pages');
        $this->assertDirectoryExists($basePath.'/routes');
        $this->assertDirectoryExists($basePath.'/stubs');
        $this->assertDirectoryExists($basePath.'/database');
        $this->assertDirectoryExists($basePath.'/database/migrations');
    }

    #[Test]
    public function package_has_required_files(): void
    {
        $basePath = __DIR__.'/../..';

        // Core files
        $this->assertFileExists($basePath.'/composer.json');
        $this->assertFileExists($basePath.'/phpunit.xml.dist');
        $this->assertFileExists($basePath.'/README.md');
        $this->assertFileExists($basePath.'/LICENSE.md');

        // Source files
        $this->assertFileExists($basePath.'/src/DualAgentUIServiceProvider.php');
        $this->assertFileExists($basePath.'/src/Http/Controllers/DashboardController.php');
        $this->assertFileExists($basePath.'/src/Commands/DualAgentUICommand.php');

        // Config files
        $this->assertFileExists($basePath.'/config/dual-agent-ui.php');

        // View files
        $this->assertFileExists($basePath.'/resources/views/app.blade.php');
        $this->assertFileExists($basePath.'/resources/js/Pages/Dashboard.vue');

        // Route files
        $this->assertFileExists($basePath.'/routes/dual-agent-ui.php');
    }

    #[Test]
    public function view_files_contain_required_content(): void
    {
        $appBlade = file_get_contents(__DIR__.'/../../resources/views/app.blade.php');

        $this->assertStringContainsString('<!DOCTYPE html>', $appBlade);
        $this->assertStringContainsString('@inertia', $appBlade);
        $this->assertStringContainsString('@inertiaHead', $appBlade);
        $this->assertStringContainsString('@vite', $appBlade);

        $dashboardVue = file_get_contents(__DIR__.'/../../resources/js/Pages/Dashboard.vue');

        $this->assertStringContainsString('<template>', $dashboardVue);
        $this->assertStringContainsString('<script setup>', $dashboardVue);
        $this->assertStringContainsString('defineProps', $dashboardVue);
    }

    #[Test]
    public function route_file_defines_dashboard_route(): void
    {
        $routeContent = file_get_contents(__DIR__.'/../../routes/dual-agent-ui.php');

        $this->assertStringContainsString('Route::get', $routeContent);
        $this->assertStringContainsString('/agent-dashboard', $routeContent);
        $this->assertStringContainsString('DashboardController', $routeContent);
        $this->assertStringContainsString('->name(\'dashboard\')', $routeContent);
    }

    #[Test]
    public function migration_stub_exists(): void
    {
        $migrationPath = __DIR__.'/../../database/migrations/create_dual_agent_ui_table.php.stub';

        $this->assertFileExists($migrationPath);

        $migrationContent = file_get_contents($migrationPath);
        $this->assertStringContainsString('Schema::create', $migrationContent);
        $this->assertStringContainsString('dual_agent_ui', $migrationContent);
    }

    #[Test]
    public function package_version_is_consistent(): void
    {
        $composerPath = __DIR__.'/../../composer.json';
        $composer = json_decode(file_get_contents($composerPath), true);

        // Check that version exists or dev-main is used
        $this->assertTrue(
            isset($composer['version']) ||
            (isset($composer['minimum-stability']) && $composer['minimum-stability'] === 'dev')
        );
    }
}
