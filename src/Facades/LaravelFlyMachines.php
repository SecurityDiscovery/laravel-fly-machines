<?php

namespace SecurityDiscovery\LaravelFlyMachines\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \SecurityDiscovery\LaravelFlyMachines\LaravelFlyMachines
 */
class LaravelFlyMachines extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \SecurityDiscovery\LaravelFlyMachines\LaravelFlyMachines::class;
    }
}
