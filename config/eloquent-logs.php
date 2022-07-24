<?php

// config for ElaborateCode/EloquentLogs
return [
    'logs_model' => \ElaborateCode\EloquentLogs\Models\EloquentLog::class,
    'logs_table' => 'eloquent_logs',

    /** @phpstan-ignore-next-line */
    'user' => \App\Models\User::class,

];
