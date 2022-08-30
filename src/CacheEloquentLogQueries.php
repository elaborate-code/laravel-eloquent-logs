<?php

namespace ElaborateCode\EloquentLogs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CacheEloquentLogQueries
{
    private bool $caching = false;

    private array $queries = [];

    public function start(): void
    {
        $this->caching = true;
    }

    public function abort(): void
    {
        $this->caching = false;
    }

    public function flushQueries(): void
    {
        $this->queries = [];
    }

    public function reset(): void
    {
        $this->caching = false;
        $this->queries = [];
    }

    public function isCaching(): bool
    {
        return $this->caching;
    }

    public function pushQuery(Model $model, string $event, ?int $user_id): void
    {
        $user_id ??= Auth::id();

        array_push(
            $this->queries,
            [
                'loggable_type' => get_class($model),
                'loggable_id' => $model->id,
                'action' => $event,
                'user_id' => $user_id,
            ]
        );
    }

    public function execute(): void
    {
        if (empty($this->queries)) {
            return;
        }

        DB::table(config('eloquent-logs.logs_table'))
            ->insert($this->queries);

        $this->reset();
    }
}
