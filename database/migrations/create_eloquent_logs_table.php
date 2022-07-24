<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create(config('eloquent-logs.logs_table') ?? 'eloquent_logs', function (Blueprint $table) {
            $table->id();
            $table->morphs('loggable');
            $table->unsignedBigInteger('user_id')->index()->nullable();
            $table->string('action');
            $table->timestamps(); // ? Not sure about having an updated_at field
            $table->softDeletes(); // ? Not sure about this
        });
    }
};
