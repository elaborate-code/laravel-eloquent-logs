<?php

namespace ElaborateCode\EloquentLogs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CacheEloquentLogQueries
{
    private static bool $caching = false;

    private static array $queries = [];

    public static function start(): void
    {
        self::$caching = true;
    }

    public static function abort(): void
    {
        self::$caching = false;
    }

    public static function flushQueries(): void
    {
        self::$queries = [];
    }

    public static function reset(): void
    {
        self::$caching = false;
        self::$queries = [];
    }

    public static function isCaching(): bool
    {
        return self::$caching;
    }

    public static function pushQuery(Model $model, string $event, ?int $user_id): void
    {
        $user_id ??= Auth::id();

        array_push(
            self::$queries,
            [
                'loggable_type' => get_class($model),
                'loggable_id' => $model->id,
                'action' => $event,
                'user_id' => $user_id,
            ]
        );
    }

    public static function execute(): void
    {
        if (empty(self::$queries)) {
            return;
        }

        DB::table(config('eloquent-logs.logs_table'))
            ->insert(self::$queries);

        self::reset();
    }
}
