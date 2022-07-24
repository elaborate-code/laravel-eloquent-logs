<?php

namespace ElaborateCode\EloquentLogs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @author medilies
 */
class EloquentLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('eloquent-logs.logs_table') ?? 'eloquent_logs';
    }

    public function loggable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        /** @phpstan-ignore-next-line */
        return $this->belongsTo(config('eloquent-logs.user') ?? \App\Models\User::class, 'id', 'user_id');
    }
}
