<?php

namespace ElaborateCode\EloquentLogs\Tests\Models;

use ElaborateCode\EloquentLogs\Concerns\HasLogs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IgnoredModel extends Model
{
    use HasLogs, SoftDeletes;

    public static array $loggableOptions = [
        'ignore' => ['created', 'updated', 'deleted', 'softDeleted', 'forceDeleted', 'restored'],
    ];

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var bool
     */
    public $timestamps = false;

    public $table = 'soft_deleted_fake_models';
}
