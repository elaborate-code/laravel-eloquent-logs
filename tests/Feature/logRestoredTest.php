<?php

use ElaborateCode\EloquentLogs\Tests\Models\SoftDeletedFakeModel;

it('logs model restored', function () {
    $fake_model = SoftDeletedFakeModel::create(['name' => 'foo']);
    $fake_model->delete();
    $fake_model->restore(); // ! Extra updated event

    $this
        ->assertDatabaseHas('eloquent_logs', [
            'action' => 'restored',
            'loggable_type' => SoftDeletedFakeModel::class,
            'loggable_id' => $fake_model->id,
        ])
        // ->assertDatabaseCount('eloquent_logs', 3)
        ->assertDatabaseCount('eloquent_logs', 4);
});
