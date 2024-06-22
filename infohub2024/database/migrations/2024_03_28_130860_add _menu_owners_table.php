<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('menu_owners', function (Blueprint $table) {
            $table->unsignedSmallInteger('menu_item_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('menu_item_id')->references('id')->on('menu_items')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->primary(['menu_item_id', 'user_id'], 'menu_item_user_primary');
        });
    }

    public function down(): void {
        Schema::dropIfExists('menu_owners');
    }
};
