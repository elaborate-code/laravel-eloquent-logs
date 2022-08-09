<?php

use ElaborateCode\EloquentLogs\Tests\Models\FakeModel;

it('logs model events', function () {
    $fake_model = FakeModel::create(['name' => 'foo']);

    $fake_model->update(['name' => 'bar']);

    $fake_model->delete();

    $this
        ->assertDatabaseHas('eloquent_logs', [
            'action' => 'created',
            'loggable_type' => FakeModel::class,
            'loggable_id' => $fake_model->id,
        ])
        ->assertDatabaseHas('eloquent_logs', [
            'action' => 'updated',
            'loggable_type' => FakeModel::class,
            'loggable_id' => $fake_model->id,
        ])
        ->assertDatabaseHas('eloquent_logs', [
            'action' => 'deleted',
            'loggable_type' => FakeModel::class,
            'loggable_id' => $fake_model->id,
        ])
        ->assertDatabaseCount('eloquent_logs', 3);
});
