<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('eloquent_logs', function (Blueprint $table) {
            $table->id();
            $table->morphs('loggable');
            $table->unsignedBigInteger('user_id')->index();
            $table->string('action');
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
