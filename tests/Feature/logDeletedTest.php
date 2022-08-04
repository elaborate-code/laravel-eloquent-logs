<?php

use ElaborateCode\EloquentLogs\Tests\Models\FakeModel;

it('logs model deleted', function () {
    $fake_model = FakeModel::create(['name' => 'foo']);
    $fake_model->delete();

    $this
        ->assertDatabaseHas('eloquent_logs', [
            'action' => 'deleted',
            'loggable_type' => FakeModel::class,
            'loggable_id' => $fake_model->id,
        ])
        ->assertDatabaseCount('eloquent_logs', 2);
});
