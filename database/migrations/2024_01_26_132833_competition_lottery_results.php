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
        Schema::create('competition_lottery_results', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique()->index();
            $table->bigInteger('competition_id')->index();
            $table->bigInteger('planned_competition_reward_id')->index();
            $table->decimal('amount', 13, 2);
            $table->bigInteger('ticket_id')->index();
            $table->dateTime('result_at')->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competition_lottery_results');
    }
};
