<?php

namespace SecurityDiscovery\LaravelFlyMachines;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use SecurityDiscovery\LaravelFlyMachines\Commands\LaravelFlyMachinesCommand;

class LaravelFlyMachinesServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-fly-machines')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel-fly-machines_table')
            ->hasCommand(LaravelFlyMachinesCommand::class);
    }
}
