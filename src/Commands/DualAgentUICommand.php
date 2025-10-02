<?php

namespace Ihasan\DualAgentUI\Commands;

use Illuminate\Console\Command;

class DualAgentUICommand extends Command
{
    public $signature = 'dual-agent-ui';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
