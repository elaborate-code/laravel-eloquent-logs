<?php

namespace Elaborate-code\LaravelEloquentLogs;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Elaborate-code\LaravelEloquentLogs\Commands\LaravelEloquentLogsCommand;

class LaravelEloquentLogsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-eloquent-logs')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel-eloquent-logs_table')
            ->hasCommand(LaravelEloquentLogsCommand::class);
    }
}
