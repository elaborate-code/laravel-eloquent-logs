<?php

namespace Elaborate-code\LaravelEloquentLogs\Commands;

use Illuminate\Console\Command;

class LaravelEloquentLogsCommand extends Command
{
    public $signature = 'laravel-eloquent-logs';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
