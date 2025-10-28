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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('first_name', 256);
            $table->string('last_name', 256);
            $table->string('username', 256);
            $table->string('national_id', 11)->nullable();
            $table->string('phone', 20)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('email', 512)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 256);
            $table->boolean('password_change_required')->default(true);
            $table->timestamp('password_changed_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('has_tfa')->default(false);
            $table->string('tfa_method', 50)->nullable();
            $table->string('tfa_secret', 256)->nullable();
            $table->smallInteger('language')->default(1);
            $table->smallInteger('type')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['uuid', 'username', 'email', 'is_active', 'has_tfa', 'type', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
