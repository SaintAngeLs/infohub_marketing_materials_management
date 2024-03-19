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
        Schema::create('branches', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name', 255)->nullable();
            $table->string('address', 150)->nullable();
            $table->string('code', 10)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('phone', 12)->nullable();
            $table->string('fax', 12)->nullable();
            $table->timestamp('fingerprint')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
