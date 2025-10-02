<?php

namespace Ihasan\DualAgentUI;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Ihasan\DualAgentUI\Commands\DualAgentUICommand;

class DualAgentUIServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('dual-agent-ui')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_dual_agent_ui_table')
            ->hasRoutes(['dual-agent-ui'])
            ->hasCommand(DualAgentUICommand::class)
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->startWith(function (InstallCommand $command) {
                        $command->info('Installing Dual Agent UI...');
                        $this->setupInertiaJs($command);
                        $this->publishFiles($command);
                        $this->updatePackageJson($command);
                        $this->updateViteConfig($command);
                        $this->addInertiaMiddleware($command);
                    })
                    ->publishAssets()
                    ->endWith(function (InstallCommand $command) {
                        $command->info('');
                        $command->info('Installation complete! Please run the following commands:');
                        $command->info('1. npm install');
                        $command->info('2. npm run build');
                        $command->info('3. Add HandleInertiaRequests middleware to your web middleware group in bootstrap/app.php');
                        $command->info('');
                    });
            });
    }

    private function setupInertiaJs(InstallCommand $command): void
    {
        $command->info('Setting up Inertia.js...');
        
        // Check if Inertia is already installed
        $composerPath = base_path('composer.json');
        $composerContent = json_decode(File::get($composerPath), true);
        
        if (!isset($composerContent['require']['inertiajs/inertia-laravel'])) {
            $command->warn('Please install Inertia.js Laravel adapter: composer require inertiajs/inertia-laravel');
        }
    }

    private function publishFiles(InstallCommand $command): void
    {
        // Publish app.blade.php
        $viewPath = base_path('resources/views/app.blade.php');
        $packageViewPath = __DIR__.'/../resources/views/app.blade.php';

        if (!File::exists($viewPath)) {
            File::copy($packageViewPath, $viewPath);
            $command->info('Published app.blade.php to resources/views/');
        } else {
            $command->warn('app.blade.php already exists, skipping...');
        }

        // Publish Dashboard.vue component
        $pagesDir = base_path('resources/js/Pages');
        if (!File::exists($pagesDir)) {
            File::makeDirectory($pagesDir, 0755, true);
        }

        $dashboardPath = $pagesDir . '/Dashboard.vue';
        $packageDashboardPath = __DIR__.'/../resources/js/Pages/Dashboard.vue';

        if (!File::exists($dashboardPath)) {
            File::copy($packageDashboardPath, $dashboardPath);
            $command->info('Published Dashboard.vue to resources/js/Pages/');
        } else {
            $command->warn('Dashboard.vue already exists, skipping...');
        }

        // Handle app.js
        $this->updateAppJs($command);
    }

    private function updateAppJs(InstallCommand $command): void
    {
        $appJsPath = base_path('resources/js/app.js');
        $stubAppJsPath = __DIR__.'/../stubs/app.js';

        if (!File::exists($appJsPath)) {
            File::copy($stubAppJsPath, $appJsPath);
            $command->info('Published app.js to resources/js/');
            return;
        }

        $appJsContent = File::get($appJsPath);
        
        // Check if Inertia is already set up
        if (Str::contains($appJsContent, '@inertiajs/vue3') && Str::contains($appJsContent, 'createInertiaApp')) {
            $command->info('app.js already has Inertia.js setup');
            return;
        }

        $command->info('Adding Inertia.js setup to existing app.js...');
        
        // Create backup
        File::copy($appJsPath, $appJsPath . '.backup.' . time());
        $command->info('Created backup: app.js.backup.' . time());

        // Always append, never replace existing content
        $inertiaImports = "import { createApp, h } from 'vue'\nimport { createInertiaApp } from '@inertiajs/vue3'";
        $inertiaSetup = "createInertiaApp({\n  resolve: name => {\n    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })\n    return pages[`./Pages/\${name}.vue`]\n  },\n  setup({ el, App, props, plugin }) {\n    createApp({ render: () => h(App, props) })\n      .use(plugin)\n      .mount(el)\n  },\n})";

        $updatedContent = $this->mergeJavaScriptContent($appJsContent, $inertiaImports, $inertiaSetup);
        
        File::put($appJsPath, $updatedContent);
        $command->info('Successfully merged Inertia.js setup with existing app.js');
    }

    private function mergeJavaScriptContent(string $existingContent, string $imports, string $setup): string
    {
        $lines = explode("\n", $existingContent);
        $mergedLines = [];
        $importsAdded = false;
        $lastImportIndex = -1;

        // First pass: find where to add imports and track existing imports
        foreach ($lines as $index => $line) {
            if (Str::startsWith(trim($line), 'import ') && !Str::contains($line, '@inertiajs/vue3')) {
                $lastImportIndex = $index;
            }
            $mergedLines[] = $line;
        }

        // Add imports after the last import or at the beginning
        if ($lastImportIndex >= 0) {
            array_splice($mergedLines, $lastImportIndex + 1, 0, $imports);
        } else {
            array_unshift($mergedLines, $imports, '');
        }

        // Add Inertia setup at the end with clear separation
        $mergedLines[] = '';
        $mergedLines[] = '// Inertia.js setup added by Dual Agent UI';
        $mergedLines[] = $setup;

        return implode("\n", $mergedLines);
    }

    private function updatePackageJson(InstallCommand $command): void
    {
        $packageJsonPath = base_path('package.json');
        
        if (File::exists($packageJsonPath)) {
            $packageJson = json_decode(File::get($packageJsonPath), true);
            
            // Add required dependencies
            $requiredDevDeps = [
                '@inertiajs/vue3' => '^2.2.4',
                '@vitejs/plugin-vue' => '^5.0.0',
                'vue' => '^3.4.0'
            ];

            $added = [];
            foreach ($requiredDevDeps as $package => $version) {
                if (!isset($packageJson['devDependencies'][$package])) {
                    $packageJson['devDependencies'][$package] = $version;
                    $added[] = $package;
                }
            }

            if (!empty($added)) {
                File::put($packageJsonPath, json_encode($packageJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                $command->info('Added dependencies to package.json: ' . implode(', ', $added));
            } else {
                $command->info('All required dependencies already present in package.json');
            }
        } else {
            $command->error('package.json not found. Please create it first.');
        }
    }

    private function updateViteConfig(InstallCommand $command): void
    {
        $viteConfigPath = base_path('vite.config.js');
        $stubViteConfigPath = __DIR__.'/../stubs/vite.config.js';

        if (!File::exists($viteConfigPath)) {
            File::copy($stubViteConfigPath, $viteConfigPath);
            $command->info('Published vite.config.js');
            return;
        }

        $viteContent = File::get($viteConfigPath);
        
        // Check if Vue plugin is already configured
        if (Str::contains($viteContent, '@vitejs/plugin-vue') || Str::contains($viteContent, 'vue()')) {
            $command->info('vite.config.js already has Vue plugin configured');
            return;
        }

        $command->info('Adding Vue plugin to existing vite.config.js...');
        
        // Create backup
        File::copy($viteConfigPath, $viteConfigPath . '.backup.' . time());
        $command->info('Created backup: vite.config.js.backup.' . time());

        // Always try to merge, never replace entirely
        $this->mergeVuePluginToViteConfig($viteConfigPath, $viteContent, $command);
    }

    private function mergeVuePluginToViteConfig(string $viteConfigPath, string $content, InstallCommand $command): void
    {
        // Simple and reliable approach: replace entire config if it doesn't have Vue
        // This prevents complex parsing issues and ensures it works
        
        if (Str::contains($content, 'vue()') && Str::contains($content, '@vitejs/plugin-vue')) {
            $command->info('Vue plugin already properly configured');
            return;
        }

        // If the config is complex or we can't reliably parse it, replace with working config
        $command->info('Replacing vite.config.js with Vue-compatible configuration...');
        
        // Extract the input array from laravel plugin if present
        $inputs = "['resources/css/app.css', 'resources/js/app.js']";
        if (preg_match('/input:\s*(\[[^\]]+\])/', $content, $matches)) {
            $inputs = $matches[1];
        }

        $newConfig = "import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: {$inputs},
            refresh: true,
        }),
        vue(),
    ],
});";

        File::put($viteConfigPath, $newConfig);
        $command->info('Successfully updated vite.config.js with Vue plugin support');
    }



    private function addInertiaMiddleware(InstallCommand $command): void
    {
        $middlewarePath = base_path('app/Http/Middleware/HandleInertiaRequests.php');
        $stubMiddlewarePath = __DIR__.'/../stubs/HandleInertiaRequests.php';

        if (!File::exists($middlewarePath)) {
            $middlewareDir = dirname($middlewarePath);
            if (!File::exists($middlewareDir)) {
                File::makeDirectory($middlewareDir, 0755, true);
            }
            
            File::copy($stubMiddlewarePath, $middlewarePath);
            $command->info('Published HandleInertiaRequests middleware');
        } else {
            $command->warn('HandleInertiaRequests middleware already exists');
        }

        // Try to automatically add middleware to bootstrap/app.php for Laravel 11
        $this->addMiddlewareToBootstrap($command);
    }

    private function addMiddlewareToBootstrap(InstallCommand $command): void
    {
        $bootstrapPath = base_path('bootstrap/app.php');
        
        if (!File::exists($bootstrapPath)) {
            $command->warn('bootstrap/app.php not found. Please add HandleInertiaRequests middleware manually.');
            $this->showManualMiddlewareInstructions($command);
            return;
        }

        $bootstrapContent = File::get($bootstrapPath);
        
        // Check if middleware is already added
        if (Str::contains($bootstrapContent, 'HandleInertiaRequests')) {
            $command->info('HandleInertiaRequests middleware already configured in bootstrap/app.php');
            return;
        }

        // Conservative approach - only try to add if we find a clear middleware pattern
        if (!Str::contains($bootstrapContent, '->withMiddleware(')) {
            $command->warn('bootstrap/app.php does not have withMiddleware configuration.');
            $this->showManualMiddlewareInstructions($command);
            return;
        }

        $command->info('Attempting to add HandleInertiaRequests middleware to bootstrap/app.php...');
        
        // Create backup
        File::copy($bootstrapPath, $bootstrapPath . '.backup.' . time());
        $command->info('Created backup: bootstrap/app.php.backup.' . time());
        
        // Try to merge middleware intelligently
        $success = $this->mergeMiddlewareToBootstrap($bootstrapContent, $bootstrapPath, $command);
        
        if (!$success) {
            $command->warn('Could not automatically add middleware due to complex bootstrap structure.');
            $this->showManualMiddlewareInstructions($command);
        }
    }

    private function mergeMiddlewareToBootstrap(string $content, string $bootstrapPath, InstallCommand $command): bool
    {
        $lines = explode("\n", $content);
        $updatedLines = [];
        $middlewareAdded = false;
        $insideWithMiddleware = false;
        $insideWebMiddleware = false;
        $braceDepth = 0;

        foreach ($lines as $line) {
            $trimmedLine = trim($line);
            
            // Track if we're inside withMiddleware function
            if (Str::contains($line, '->withMiddleware(')) {
                $insideWithMiddleware = true;
                $braceDepth = 0;
            }

            if ($insideWithMiddleware) {
                $braceDepth += substr_count($line, '{') - substr_count($line, '}');
                
                // Check if we're inside existing web middleware configuration
                if (Str::contains($line, '$middleware->web(')) {
                    $insideWebMiddleware = true;
                }

                // If we find the end of withMiddleware and haven't added middleware yet
                if ($braceDepth <= 0 && !$middlewareAdded && Str::contains($line, '}')) {
                    // Add web middleware before the closing brace
                    $indent = $this->getIndentationFromLine($line);
                    $middlewareLines = [
                        $indent . '$middleware->web(append: [',
                        $indent . '    \App\Http\Middleware\HandleInertiaRequests::class,',
                        $indent . ']);'
                    ];
                    
                    // Insert before this closing line
                    $updatedLines = array_merge($updatedLines, $middlewareLines);
                    $middlewareAdded = true;
                    $insideWithMiddleware = false;
                }

                // If we're inside existing web middleware, try to add to it
                if ($insideWebMiddleware && Str::contains($line, ']);') && !$middlewareAdded) {
                    // Add before the closing of web middleware
                    $indent = $this->getIndentationFromLine($line);
                    $updatedLines[] = $indent . '    \App\Http\Middleware\HandleInertiaRequests::class,';
                    $middlewareAdded = true;
                    $insideWebMiddleware = false;
                }
            }

            $updatedLines[] = $line;
        }

        if ($middlewareAdded) {
            File::put($bootstrapPath, implode("\n", $updatedLines));
            $command->info('Successfully merged HandleInertiaRequests middleware with existing bootstrap/app.php');
            return true;
        }

        return false;
    }

    private function showManualMiddlewareInstructions(InstallCommand $command): void
    {
        $command->warn('');
        $command->warn('MANUAL STEP REQUIRED: Add HandleInertiaRequests to your web middleware group in bootstrap/app.php:');
        $command->warn('->withMiddleware(function (Middleware $middleware) {');
        $command->warn('    $middleware->web(append: [');
        $command->warn('        \App\Http\Middleware\HandleInertiaRequests::class,');
        $command->warn('    ]);');
        $command->warn('})');
        $command->warn('');
    }

    private function getIndentationFromLine(string $line): string
    {
        return str_repeat(' ', strlen($line) - strlen(ltrim($line)));
    }

}
