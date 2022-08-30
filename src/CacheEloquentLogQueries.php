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
        if ($this->isCaching()) {
            throw new \Exception('Misplaced usage of the CacheEloquentLogQueries::start() queries cache is already set');
        }

        $this->caching = true;
    }

    /**
     * Suspends caching without affecting the queries cache
     */
    public function suspend(): self
    {
        if (! $this->isCaching()) {
            throw new \Exception("Misplaced usage of the CacheEloquentLogQueries::suspend() queries cache isn't set");
        }

        $this->caching = false;

        return $this;
    }

    /**
     * Flushes the queries cache without halting the caching process
     */
    public function flushQueries(): self
    {
        $this->queries = [];

        return $this;
    }

    /**
     * Flushes the queries cache and suspends further caching
     */
    public function reset(): void
    {
        if (! $this->isCaching()) {
            throw new \Exception("Misplaced usage of the CacheEloquentLogQueries::reset() queries cache isn't set");
        }

        $this->suspend()->flushQueries();
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
                /** @phpstan-ignore-next-line */
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
