<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('parent_id')->nullable()->comment('Parent menu item');
            $table->unsignedBigInteger('owner_id')->nullable()->comment('Person responsible for the tab');
            $table->unsignedBigInteger('user_id')->nullable()->comment('Who created');
            $table->unsignedSmallInteger('banner_id')->default(0)->comment('0 - random');
            $table->string('name', 255)->nullable();
            $table->string('slug', 255)->nullable();
            $table->unsignedTinyInteger('position')->nullable();
            $table->dateTime('start')->nullable();
            $table->dateTime('end')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0- inactive, 1- active');
            $table->tinyInteger('archived')->default(0)->comment('1 if archived');
            $table->dateTime('archived_at')->nullable();
            $table->unsignedBigInteger('archived_by')->nullable()->comment('Who archived the item');
            $table->timestamp('fingerprint')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('created_at')->nullable();

            // Nested Set columns
            
            $table->unsignedInteger('lft')->nullable();
            $table->unsignedInteger('rgt')->nullable();
            $table->unsignedInteger('lvl')->nullable();

            // Optional: Migrate::columns($table, new NestedSetConfig()); - adjust as necessary

            $table->foreign('parent_id')->references('id')->on('menu_items')->onDelete('SET NULL');
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('SET NULL');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL');
            $table->foreign('archived_by')->references('id')->on('users')->onDelete('SET NULL');
        });
    }

    public function down(): void {
        Schema::dropIfExists('menu_items');
    }
};
