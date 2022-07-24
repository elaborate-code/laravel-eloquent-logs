<?php

namespace ElaborateCode\EloquentLogs;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class EloquentLogsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('eloquent-logs')
            ->hasConfigFile()
            ->hasMigration('create_eloquent_logs_table')
            ->runsMigrations();
    }
}
