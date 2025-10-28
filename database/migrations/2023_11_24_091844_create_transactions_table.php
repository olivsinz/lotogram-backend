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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->smallInteger('method_id')->nullable();
            $table->string('method_tx_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->decimal('amount', 13,2);
            $table->decimal('withdrawable_amount', 13,2)->default(0);
            $table->decimal('bonus_amount', 13,2)->default(0);
            $table->smallInteger('purpose');
            $table->smallInteger('type');
            $table->smallInteger('status');
            $table->timestamps();
            $table->index(['uuid', 'method_id', 'method_tx_id', 'user_id', 'purpose', 'type', 'status'], 'transactions_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
