<?php

namespace ElaborateCode\EloquentLogs\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

trait HasLogs
{
    public bool $loggableBeingRestoredFlag = false;

    public static function bootHasLogs(): void
    {
        foreach (['created', 'updated', 'deleted'] as $event) {
            if (! self::isIgnored($event)) {
                self::{$event}([self::class, "{$event}Handler"]);
            }
        }

        if (in_array('Illuminate\Database\Eloquent\SoftDeletes', (class_uses(self::class)))) {
            foreach (['softDeleted', 'forceDeleted', 'restored'] as $event) {
                if (! self::isIgnored($event)) {
                    self::{$event}([self::class, "{$event}Handler"]);
                }
            }

            // A work around Laravel firing many events when restored
            if (! self::isIgnored('restored')) {
                self::restoring([self::class, 'setLoggableBeingRestoredFlag']);
            }
        }
    }

    public static function log(Model $model, string $event): void
    {
        $model->eloquentLogs()->create([
            'action' => $event,
            'user_id' => Auth::id(),
        ]);
    }

    public static function isIgnored(string $event): bool
    {
        if (
            ! isset(self::$loggableOptions) ||
            ! isset(self::$loggableOptions['ignore'])
        ) {
            return false;
        }

        if (! is_array(self::$loggableOptions['ignore'])) {
            throw new \Exception('self::$loggableOptions[\'ignore\'] must be an array');
        }

        if (in_array($event, self::$loggableOptions['ignore'])) {
            return true;
        }

        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | Handlers
    |--------------------------------------------------------------------------
    |
    */

    /**
     * Handles the created event
     */
    public static function createdHandler(Model $model): void
    {
        self::log($model, self::eventPhraseFromHandlerName(__FUNCTION__));
    }

    /**
     * Handles the updated event
     */
    public static function updatedHandler(Model $model): void
    {
        if (self::isLoggableBeingRestored($model)) {
            // This is a restored event so don't log!
            return;
        }

        self::log($model, self::eventPhraseFromHandlerName(__FUNCTION__));
    }

    /**
     * Handles the deleted event
     */
    public static function deletedHandler(Model $model): void
    {
        if (in_array('Illuminate\Database\Eloquent\SoftDeletes', (class_uses(self::class)))) {
            // This is a softDeleted or a forceDeleted event so don't log!
            return;
        }

        self::log($model, self::eventPhraseFromHandlerName(__FUNCTION__));
    }

    /**
     * Handles the softDeleted event
     */
    public static function softDeletedHandler(Model $model): void
    {
        self::log($model, self::eventPhraseFromHandlerName(__FUNCTION__));
    }

    /**
     * Handles the forceDeleted event
     */
    public static function forceDeletedHandler(Model $model): void
    {
        self::log($model, self::eventPhraseFromHandlerName(__FUNCTION__));
    }

    /**
     * Handles the restored event
     */
    public static function restoredHandler(Model $model): void
    {
        self::log($model, self::eventPhraseFromHandlerName(__FUNCTION__));

        self::resetLoggableBeingRestoredFlag($model);
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    |
    */

    public function eloquentLogs(): MorphMany
    {
        return $this->morphMany(config('eloquent-logs.logs_model') ?? ElaborateCode\EloquentLogs\Models\EloquentLog::class, 'loggable');
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    |
    */

    /**
     * Transforms the string input to lower case separated by spaces
     * and rejects the 'Handler' substring if found
     */
    public static function eventPhraseFromHandlerName(string $str): string
    {
        return Str::snake(str_replace('Handler', '', $str), ' ');
    }

    public static function setLoggableBeingRestoredFlag(Model $model): void
    {
        $model->loggableBeingRestoredFlag = true;
    }

    public static function resetLoggableBeingRestoredFlag(Model $model): void
    {
        $model->loggableBeingRestoredFlag = false;
    }

    public static function isLoggableBeingRestored(Model $model): bool
    {
        return $model->loggableBeingRestoredFlag;
    }
}
