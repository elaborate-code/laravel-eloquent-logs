<?php

use ElaborateCode\EloquentLogs\CacheEloquentLogQueries;
use ElaborateCode\EloquentLogs\Tests\Models\FakeModel;
use function Pest\Laravel\assertDatabaseCount;

it('caches logs', function () {
    CacheEloquentLogQueries::start();

    $fake_model = FakeModel::create(['name' => 'foo']);

    $fake_model->update(['name' => 'bar']);

    $fake_model->delete();

    assertDatabaseCount('eloquent_logs', 0);

    CacheEloquentLogQueries::execute();

    assertDatabaseCount('eloquent_logs', 3);
});
