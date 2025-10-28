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
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreign(['method_id'], 'fk_method')->references(['id'])->on('methods')->onDelete('RESTRICT');
            $table->foreign(['user_id'], 'fk_user')->references(['id'])->on('users')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign('fk_method');
            $table->dropForeign('fk_user');
        });
    }
};
