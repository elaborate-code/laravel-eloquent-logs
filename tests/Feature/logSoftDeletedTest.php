<?php

use ElaborateCode\EloquentLogs\Tests\Models\SoftDeletedFakeModel;

it('logs model SOFT deletion', function () {
    $fake_model = SoftDeletedFakeModel::create(['name' => 'foo']);
    $fake_model->delete();

    $this
        ->assertDatabaseHas('eloquent_logs', [
            'action' => 'soft deleted',
            'loggable_type' => SoftDeletedFakeModel::class,
            'loggable_id' => $fake_model->id,
        ])
        ->assertDatabaseCount('eloquent_logs', 2);
});
