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
        Schema::create('planned_competitions', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique()->index();
            $table->string('title')->unique();
            $table->smallInteger('cost_percentage');
            $table->smallInteger('min_purchased_ticket_user')->nullable(); // min_purchased_ticket_count -> min_purchased_user_count
            $table->smallInteger('interval_minutes')->nullable()->index();
            $table->dateTime('planned_finish_at')->nullable()->index();
            $table->smallInteger('status')->index();
            $table->smallInteger('real_time_count');
            $table->smallInteger('ticket_count');
            $table->decimal('ticket_amount', 13, 0);
            $table->smallInteger('min_ticket_number');
            $table->smallInteger('max_ticket_number');
            $table->smallInteger('octet');
            $table->smallInteger('manipulate_wait_secs_after_bot');
            $table->smallInteger('manipulate_wait_secs_after_user');
            $table->smallInteger('daily_limit');
            $table->smallInteger('stats_daily_count')->default(0);
            $table->smallInteger('cancellation_time_limit')->default(10);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planned_competitions');
    }
};
