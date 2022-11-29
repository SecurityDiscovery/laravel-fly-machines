<?php

namespace SecurityDiscovery\LaravelFlyMachines\Commands;

use Illuminate\Console\Command;

class LaravelFlyMachinesCommand extends Command
{
    public $signature = 'laravel-fly-machines';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
