<?php

namespace Elaborate-code\LaravelEloquentLogs\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Elaborate-code\LaravelEloquentLogs\LaravelEloquentLogs
 */
class LaravelEloquentLogs extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-eloquent-logs';
    }
}
