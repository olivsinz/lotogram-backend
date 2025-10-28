<?php

use App\Enum\CompetitionTicketType;
use App\Enum\UserType;
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
        Schema::create('competition_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique()->index();
            $table->bigInteger('competition_id')->index();
            $table->decimal('amount', 13, 2);
            $table->string('number', 20);
            $table->string('number_order', 20)->default(0);
            $table->bigInteger('user_id')->nullable()->index();
            $table->dateTime('bet_at')->nullable()->index();
            $table->smallInteger('won')->nullable()->index();
            $table->smallInteger('type')->default(CompetitionTicketType::User->value)->index(); // us ?
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competition_tickets');
    }
};
