<?php

use ElaborateCode\EloquentLogs\Tests\Models\FakeModel;

it(description: 'logs model created')
    ->tap(
        fn (): FakeModel => FakeModel::create([
            'name' => 'foo',
        ])
    )
    ->assertDatabaseHas('eloquent_logs', [
        'action' => 'created',
        'loggable_type' => FakeModel::class,
        'loggable_id' => 1,
    ])
    ->assertDatabaseCount('eloquent_logs', 1);
