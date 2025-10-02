<?php

namespace Ihasan\DualAgentUI;

use Illuminate\Support\Facades\File;
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
                        $viewPath = base_path('resources/views/app.blade.php');
                        $packageViewPath = __DIR__.'/../resources/views/app.blade.php';

                        if (!File::exists($viewPath)) {
                            File::copy($packageViewPath, $viewPath);
                            $command->info('Published app.blade.php to resources/views/');
                        } else {
                            $command->warn('app.blade.php already exists, skipping...');
                        }
                    })
                    ->publishAssets()
                    ->endWith(function (InstallCommand $command) {
                        $command->info('Installation complete! Run `npm install && npm run build` to compile assets.');
                    });
            });
    }

}
