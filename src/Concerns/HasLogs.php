<?php

namespace ElaborateCode\EloquentLogs\Concerns;

use ElaborateCode\EloquentLogs\EloquentLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait HasLogs
{
    public static function bootHasLogs(): void
    {
        self::created(callback: fn ($model) => self::log($model, 'created'));
        self::updated(callback: fn ($model) => self::log($model, 'updated'));
    }

    public static function log(Model $model, string $event)
    {
        $model->eloquentLogs()->create([
            'action' => $event,
            'user_id' => Auth::id(),
        ]);
    }

    public function eloquentLogs()
    {
        return $this->morphMany(EloquentLog::class, 'loggable');
    }
}
