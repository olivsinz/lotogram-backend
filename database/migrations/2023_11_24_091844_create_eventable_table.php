<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eventable', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->bigInteger('event_id');
            $table->bigInteger('ownerable_id');
            $table->string('ownerable_type');
            $table->text('description')->nullable();
            $table->timestamp('expired_at', 6)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('eventable');
    }
};
