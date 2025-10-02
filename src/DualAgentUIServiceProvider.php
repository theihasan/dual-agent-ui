<?php

namespace Ihasan\DualAgentUI;

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
            ->hasCommand(DualAgentUICommand::class);
    }
}
