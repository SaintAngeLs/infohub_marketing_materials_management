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
            $table->unsignedSmallInteger('users_groups_id')->nullable();
            $table->unsignedSmallInteger('branch_id')->nullable();
            $table->string('name', 100)->nullable();
            $table->string('surname', 100)->nullable();
            $table->string('email', 100)->nullable()->unique();
            $table->string('phone', 15)->nullable();
            $table->string('password', 70)->nullable();
            $table->text('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->timestamp('two_factor_confirmed_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->unsignedTinyInteger('password_valid')->default(90)->comment('password validity in days');
            $table->dateTime('password_last_changed')->nullable();
            $table->string('token', 100)->nullable();
            $table->dateTime('token_time')->nullable();
            $table->dateTime('last_login')->nullable();
            $table->tinyInteger('active')->default(0)->comment('1 - active, 0 - inactive');
            $table->dateTime('created_at')->nullable();
            $table->timestamp('fingerprint')->useCurrent()->useCurrentOnUpdate();
            $table->string('remember_token', 100)->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('users_groups_id')->references('id')->on('users_groups')->onDelete('set null');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('set null');
        });

        // Schema::create('password_reset_tokens', function (Blueprint $table) {
        //     $table->string('email')->primary();
        //     $table->string('token');
        //     $table->timestamp('created_at')->nullable();
        // });

    //     Schema::create('sessions', function (Blueprint $table) {
    //         $table->string('id')->primary();
    //         $table->foreignId('user_id')->nullable()->index();
    //         $table->string('ip_address', 45)->nullable();
    //         $table->text('user_agent')->nullable();
    //         $table->longText('payload');
    //         $table->integer('last_activity')->index();
    //     });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
