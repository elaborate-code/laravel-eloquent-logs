<?php

use ElaborateCode\EloquentLogs\Tests\Models\FakeModel;

it(description: 'logs loggable model creation')
    ->tap(
        fn (): FakeModel => FakeModel::create([
            'name' => 'foo',
            'meta' => 'bar',
        ])
    )->assertDatabaseHas('eloquent_logs', [
        'action' => 'created',
        'loggable_type' => FakeModel::class,
        'loggable_id' => 1,
    ]);

it('logs loggable model update', function () {
    $fake_model = FakeModel::create([
        'name' => 'foo',
        'meta' => 'bar',
    ]);

    $loggable_id = $fake_model->id;

    $fake_model->update([
        'name' => 'bar',
        'meta' => 'foo',
    ]);

    $this->assertDatabaseHas('eloquent_logs', [
        'action' => 'updated',
        'loggable_type' => FakeModel::class,
        'loggable_id' => $loggable_id,
    ]);
});

it('logs loggable model FORCED deletion', function () {
    $fake_model = FakeModel::create([
        'name' => 'foo',
        'meta' => 'bar',
    ]);

    $loggable_id = $fake_model->id;

    $fake_model->forceDelete();

    $this->assertDatabaseHas('eloquent_logs', [
        'action' => 'force deleted',
        'loggable_type' => FakeModel::class,
        'loggable_id' => $loggable_id,
    ]);
});

it('logs loggable model SOFT deletion', function () {
    $fake_model = FakeModel::create([
        'name' => 'foo',
        'meta' => 'bar',
    ]);

    $loggable_id = $fake_model->id;

    $fake_model->delete();

    $this->assertDatabaseHas('eloquent_logs', [
        'action' => 'soft deleted',
        'loggable_type' => FakeModel::class,
        'loggable_id' => $loggable_id,
    ]);
});

it('logs loggable model restoration', function () {
    $fake_model = FakeModel::create([
        'name' => 'foo',
        'meta' => 'bar',
    ]);

    $loggable_id = $fake_model->id;

    $fake_model->delete();
    $fake_model->restore();

    $this->assertDatabaseHas('eloquent_logs', [
        'action' => 'restored',
        'loggable_type' => FakeModel::class,
        'loggable_id' => $loggable_id,
    ]);
});
