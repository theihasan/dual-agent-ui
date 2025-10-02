<?php

namespace Ihasan\DualAgentUI\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Ihasan\DualAgentUI\DualAgentUI
 */
class DualAgentUI extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Ihasan\DualAgentUI\DualAgentUI::class;
    }
}
