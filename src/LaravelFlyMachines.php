<?php

namespace SecurityDiscovery\LaravelFlyMachines;

class LaravelFlyMachines
{
    public static function machines(string $appName): API\Machines
    {
        return new API\Machines($appName);
    }
}
