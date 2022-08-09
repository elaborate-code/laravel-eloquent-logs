<?php

use ElaborateCode\EloquentLogs\Tests\Models\SoftDeletedFakeModel;

it('logs softDelete trait events', function () {
    $fake_model = SoftDeletedFakeModel::create(['name' => 'foo']);

    $fake_model->delete();

    $fake_model->restore();

    $fake_model->forceDelete();

    $this
        ->assertDatabaseHas('eloquent_logs', [
            'action' => 'soft deleted',
            'loggable_type' => SoftDeletedFakeModel::class,
            'loggable_id' => $fake_model->id,
        ])
        ->assertDatabaseHas('eloquent_logs', [
            'action' => 'restored',
            'loggable_type' => SoftDeletedFakeModel::class,
            'loggable_id' => $fake_model->id,
        ])
        ->assertDatabaseHas('eloquent_logs', [
            'action' => 'force deleted',
            'loggable_type' => SoftDeletedFakeModel::class,
            'loggable_id' => $fake_model->id,
        ])
        ->assertDatabaseCount('eloquent_logs', 4);
});
