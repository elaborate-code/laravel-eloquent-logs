<?php

namespace ElaborateCode\EloquentLogs\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;

trait HasLogs
{
    public static function bootHasLogs(): void
    {
        self::created(callback: fn ($model) => self::log($model, 'created'));
        self::updated(callback: fn ($model) => self::log($model, 'updated'));
        self::deleted(callback: fn ($model) => self::log($model, 'deleted'));

        if (in_array('Illuminate\Database\Eloquent\SoftDeletes', (class_uses(self::class)))) {
            self::softDeleted(callback: fn ($model) => self::log($model, 'soft deleted'));
            self::restored(callback: fn ($model) => self::log($model, 'restored'));
            self::forceDeleted(callback: fn ($model) => self::log($model, 'force deleted'));
        }
    }

    public static function log(Model $model, string $event): void
    {
        $model->eloquentLogs()->create(['action' => $event, 'user_id' => Auth::id(),]);
    }

    public function eloquentLogs(): MorphMany
    {
        return $this->morphMany(config('eloquent-logs.logs_model') ?? ElaborateCode\EloquentLogs\EloquentLog::class, 'loggable');
    }
}
