<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('competitions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('planned_competition_id')->index();
            $table->string('uuid')->unique();
            $table->smallInteger('status')->index();
            $table->smallInteger('is_settled_for_bots')->default(0);
            $table->dateTime('planned_finish_at')->index();
            $table->dateTime('bet_started_at')->nullable();
            $table->dateTime('bet_finished_at')->nullable();
            $table->dateTime('result_started_at')->nullable();
            $table->dateTime('result_finished_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competitions');
    }
};
