<?php

namespace Ihasan\DualAgentUI;

use Illuminate\Support\Facades\Route;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Ihasan\DualAgentUI\Console\Commands\PublishAssetsCommand;
use Inertia\Inertia;

class DualAgentUIServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('dual-agent-ui')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_dual_agent_ui_table')
            ->hasCommand(PublishAssetsCommand::class)
            ->hasInstallCommand(function(InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->startWith(function(InstallCommand $command) {
                        $command->info('Publishing Dual Agent UI assets...');
                        $command->call('vendor:publish', [
                            '--tag' => 'dual-agent-ui-assets',
                            '--force' => true,
                        ]);
                    })
                    ->askToStarRepoOnGitHub('theihasan/dual-agent-ui')
                    ->endWith(function(InstallCommand $command) {
                        $command->info('');
                        $command->info('Dual Agent UI installed successfully!');
                        $command->info('');
                        $command->line('📊 Access dashboard at: ' . config('app.url') . '/' . config('dual-agent-ui.path', 'agent-dashboard'));
                        $command->info('');
                        $command->comment('You can now access the dashboard!');
                    });
            });

        // Publish pre-built assets to public directory
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../public/build' => public_path('vendor/dual-agent-ui/build'),
            ], 'dual-agent-ui-assets');
        }
    }

    public function bootingPackage(): void
    {
        // Register routes with prefix and middleware from config
        $this->registerRoutes();
    }

    protected function registerRoutes(): void
    {
        if (! $this->app->routesAreCached()) {
            Route::group([
                'prefix' => config('dual-agent-ui.path', 'agent-dashboard'),
                'middleware' => config('dual-agent-ui.middleware', ['web']),
                'namespace' => 'Ihasan\\DualAgentUI\\Http\\Controllers',
            ], function () {
                $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
            });
        }
    }

    public function packageBooted(): void
    {
        // Set root view for this package's Inertia responses
        Inertia::setRootView('dual-agent-ui::root');
    }

    public function packageRegistered(): void
    {
        // Any custom registration logic here
    }



}
