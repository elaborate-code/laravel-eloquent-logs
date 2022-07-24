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
        self::trashed(callback: fn ($model) => self::log($model, 'trashed'));
        self::trashed(callback: fn ($model) => self::log($model, 'trashed'));
        self::restored(callback: fn ($model) => self::log($model, 'restored'));
        self::forceDeleted(callback: fn ($model) => self::log($model, 'forceDeleted'));
        self::deleted(callback: fn ($model) => self::log($model, 'deleted'));
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
