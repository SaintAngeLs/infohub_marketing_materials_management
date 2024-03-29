<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_group_menu_item', function (Blueprint $table) {
            $table->unsignedSmallInteger('user_group_id');
            $table->unsignedSmallInteger('menu_item_id');
            $table->timestamps();
            $table->foreign('user_group_id')->references('id')->on('users_groups')->onDelete('cascade');
            $table->foreign('menu_item_id')->references('id')->on('menu_items')->onDelete('cascade');
            $table->primary(['user_group_id', 'menu_item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_group_menu_item');
    }
};
