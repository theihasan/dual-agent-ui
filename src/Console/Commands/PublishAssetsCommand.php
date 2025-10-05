<?php

namespace Ihasan\DualAgentUI\Console\Commands;

use Illuminate\Console\Command;

class PublishAssetsCommand extends Command
{
    protected $signature = 'dual-agent-ui:publish-assets {--force : Overwrite existing files}';

    protected $description = 'Publish Dual Agent UI assets';

    public function handle(): int
    {
        $this->info('Publishing Dual Agent UI assets...');

        $this->call('vendor:publish', [
            '--tag' => 'dual-agent-ui-assets',
            '--force' => $this->option('force'),
        ]);

        $this->components->info('Assets published successfully!');

        return self::SUCCESS;
    }
}
