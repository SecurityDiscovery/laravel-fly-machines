<?php

namespace SecurityDiscovery\LaravelFlyMachines;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

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
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        $this->app->bind('laravel-fly-machines', function () {
            return new LaravelFlyMachines();
        });
    }
}
