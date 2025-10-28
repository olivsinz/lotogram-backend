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
        Schema::create('planned_competition_rewards', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique()->index();
            $table->string('title', 255);
            $table->bigInteger('planned_competition_id')->index();
            $table->decimal('percentage', 13, 2);
            $table->smallInteger('type');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planned_competition_rewards');
    }
};
