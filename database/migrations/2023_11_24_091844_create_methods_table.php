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
        Schema::create('methods', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->string('name');
            $table->boolean('is_active');
            $table->boolean('deposit_status');
            $table->boolean('withdraw_status');
            $table->boolean('worker_status');
            $table->string('slug');
            $table->smallInteger('type');
            $table->string('panel_domain');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('methods');
    }
};
