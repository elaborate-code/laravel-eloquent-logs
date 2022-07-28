<?php

use ElaborateCode\EloquentLogs\Tests\Models\FakeModel;

it('logs model update', function () {
    $fake_model = FakeModel::create(['name' => 'foo']);
    $fake_model->update(['name' => 'bar']);

    $this
        ->assertDatabaseHas('eloquent_logs', [
            'action' => 'updated',
            'loggable_type' => FakeModel::class,
            'loggable_id' => $fake_model->id,
        ])
        ->assertDatabaseCount('eloquent_logs', 2);
});
