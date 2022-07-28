<?php

use ElaborateCode\EloquentLogs\Tests\Models\SoftDeletedFakeModel;

it('logs model force deleted', function () {
    $fake_model = SoftDeletedFakeModel::create(['name' => 'foo']);
    $fake_model->forceDelete();

    $this
        ->assertDatabaseHas('eloquent_logs', [
            'action' => 'force deleted',
            'loggable_type' => SoftDeletedFakeModel::class,
            'loggable_id' => $fake_model->id,
        ])
        ->assertDatabaseCount('eloquent_logs', 2);
});
