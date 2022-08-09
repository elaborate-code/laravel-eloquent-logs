<?php

use ElaborateCode\EloquentLogs\Tests\Models\IgnoredModel;

it('donsn\'t log anything', function () {
    $fake_model = IgnoredModel::create(['name' => 'foo']);

    $fake_model->update(['name' => 'bar']);

    $fake_model->delete();

    $fake_model->restore();

    $fake_model->forceDelete();

    $this
        ->assertDatabaseMissing('eloquent_logs', [
            'action' => 'created',
            'loggable_type' => IgnoredModel::class,
            'loggable_id' => 1,
        ])
        ->assertDatabaseMissing('eloquent_logs', [
            'action' => 'updated',
            'loggable_type' => IgnoredModel::class,
            'loggable_id' => $fake_model->id,
        ])
        ->assertDatabaseMissing('eloquent_logs', [
            'action' => 'soft deleted',
            'loggable_type' => IgnoredModel::class,
            'loggable_id' => $fake_model->id,
        ])
        ->assertDatabaseMissing('eloquent_logs', [
            'action' => 'restored',
            'loggable_type' => IgnoredModel::class,
            'loggable_id' => $fake_model->id,
        ])
        ->assertDatabaseMissing('eloquent_logs', [
            'action' => 'force deleted',
            'loggable_type' => IgnoredModel::class,
            'loggable_id' => $fake_model->id,
        ])
        ->assertDatabaseCount('eloquent_logs', 0);
});
