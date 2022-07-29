<?php

namespace ElaborateCode\EloquentLogs\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;

trait HasLogs
{
    public bool $loggableIsBeingRestored = false;

    public static function bootHasLogs(): void
    {
        self::created(callback: function ($model) {
            self::log($model, 'created');
        });

        self::updated(callback: function ($model) {
            if (isset($model->loggableIsBeingRestored) && $model->loggableIsBeingRestored) {
                // This is a restored event so don't log!
                return;
            }

            self::log($model, 'updated');
        });

        self::deleted(callback: function ($model) {
            if (in_array('Illuminate\Database\Eloquent\SoftDeletes', (class_uses(self::class)))) {
                // This is a softDeleted or a forceDeleted event so don't log!
                return;
            }

            self::log($model, 'deleted');
        });

        if (! in_array('Illuminate\Database\Eloquent\SoftDeletes', (class_uses(self::class)))) {
            // This model will not fire restore, soft delete and force delete events!
            return;
        }

        self::softDeleted(callback: function ($model) {
            self::log($model, 'soft deleted');
        });

        self::forceDeleted(callback: function ($model) {
            self::log($model, 'force deleted');
        });

        self::restored(callback: function ($model) {
            self::log($model, 'restored');

            $model->loggableIsBeingRestored = false;
        });

        self::restoring(callback: function ($model) {
            $model->loggableIsBeingRestored = true;
        });
    }

    public static function log(Model $model, string $event): void
    {
        $model->eloquentLogs()->create([
            'action' => $event,
            'user_id' => Auth::id(),
        ]);
    }

    public function eloquentLogs(): MorphMany
    {
        return $this->morphMany(config('eloquent-logs.logs_model') ?? ElaborateCode\EloquentLogs\Models\EloquentLog::class, 'loggable');
    }
}
