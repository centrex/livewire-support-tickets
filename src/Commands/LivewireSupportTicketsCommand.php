<?php

declare(strict_types = 1);

namespace Centrex\LivewireSupportTickets\Commands;

use Illuminate\Console\Command;

class LivewireSupportTicketsCommand extends Command
{
    public $signature = 'livewire-support-tickets';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
