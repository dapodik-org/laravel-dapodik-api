<?php

namespace Dapodik\Laravel\API;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class APIServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-dapodik-api')
            ->hasConfigFile();
    }

    public function registeringPackage(): void
    {
        $this->app->singleton('dapodik.api.laravel', function ($app) {
            return new APIManager($app);
        });
    }
}
