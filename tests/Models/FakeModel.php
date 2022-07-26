<?php

namespace ElaborateCode\EloquentLogs\Tests\Models;

use ElaborateCode\EloquentLogs\Concerns\HasLogs;
use Illuminate\Database\Eloquent\Model;

class FakeModel extends Model
{
    use HasLogs;

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var bool
     */
    public $timestamps = false;
}
