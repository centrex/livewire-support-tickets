<?php

declare(strict_types = 1);

namespace Centrex\LivewireSupportTickets\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Centrex\LivewireSupportTickets\LivewireSupportTickets
 */
class LivewireSupportTickets extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Centrex\LivewireSupportTickets\LivewireSupportTickets::class;
    }
}
