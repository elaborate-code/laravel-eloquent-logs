<?php

namespace ElaborateCode\EloquentLogs\Facades;

use Illuminate\Support\Facades\Facade;

class CacheEloquentLogQueries extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \ElaborateCode\EloquentLogs\CacheEloquentLogQueries::class;
    }
}
