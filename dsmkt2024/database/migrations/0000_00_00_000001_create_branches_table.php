<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            $table->string('email', 255)->nullable();
            $table->timestamps();
            // $table->timestamp('fingerprint')->useCurrent();
        });

        DB::table('branches')->insert([
            ['name' => 'Branch 1', 'address' => '123 Main St', 'code' => 'B001', 'city' => 'City1', 'phone' => '1234567890', 'email' => 'branch1@example.com'],
            ['name' => 'Branch 2', 'address' => '456 Elm St', 'code' => 'B002', 'city' => 'City2', 'phone' => '0987654321', 'email' => 'branch2@example.com'],
            // Add more branches as needed
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
