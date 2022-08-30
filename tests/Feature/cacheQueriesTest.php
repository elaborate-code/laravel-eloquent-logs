<?php

use ElaborateCode\EloquentLogs\Facades\CacheEloquentLogQueries;
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

it('flushes queries cache', function () {
    CacheEloquentLogQueries::start();

    $fake_model = FakeModel::create(['name' => 'foo']);

    $fake_model->update(['name' => 'bar']);

    $fake_model->delete();

    assertDatabaseCount('eloquent_logs', 0);

    CacheEloquentLogQueries::flushQueries();

    CacheEloquentLogQueries::execute();

    assertDatabaseCount('eloquent_logs', 0);
});

it('resets queries cache', function () {
    CacheEloquentLogQueries::start();

    $fake_model = FakeModel::create(['name' => 'foo']);

    $fake_model->update(['name' => 'bar']);

    $fake_model->delete();

    assertDatabaseCount('eloquent_logs', 0);

    CacheEloquentLogQueries::reset();

    CacheEloquentLogQueries::execute();

    assertDatabaseCount('eloquent_logs', 0);
});

it('suspends queries cache', function () {
    CacheEloquentLogQueries::start();

    $fake_model = FakeModel::create(['name' => 'foo']);

    CacheEloquentLogQueries::suspend();

    $fake_model->update(['name' => 'bar']); // gets instantly logged

    CacheEloquentLogQueries::start();

    $fake_model->delete();

    assertDatabaseCount('eloquent_logs', 1);

    CacheEloquentLogQueries::execute();

    assertDatabaseCount('eloquent_logs', 3);
});
