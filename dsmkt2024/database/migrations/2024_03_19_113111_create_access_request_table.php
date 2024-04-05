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
        Schema::create('access_requests', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('company_name', 255)->nullable();
            $table->string('name', 100)->nullable();
            $table->string('surname', 100)->nullable();
            $table->string('phone', 12)->nullable();
            $table->string('email', 100)->nullable();
            $table->tinyInteger('status')->default(0)->comment('0- waiting, 1- accepted, 2- rejected');
            $table->foreignId('accepted_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('refused_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('refused_comment')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->timestamp('fingerprint')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('access_requests');
    }
};
