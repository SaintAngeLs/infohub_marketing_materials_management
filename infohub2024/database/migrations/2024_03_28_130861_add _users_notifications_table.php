<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('users_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedSmallInteger('menu_item_id');
            $table->unsignedTinyInteger('frequency')->comment('0 - nigdy, 1 - raz dziennie, 2 - przy każdej zmianie');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('menu_item_id')->references('id')->on('menu_items')->onDelete('cascade');

            $table->unique(['user_id', 'menu_item_id'], 'user_menu_item_unique');
        });
    }

    public function down(): void {
        Schema::dropIfExists('users_notifications');
    }
};
