<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropForeign(['owner_id']);
            $table->dropColumn('owner_id');
        });
    }

    public function down()
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->unsignedBigInteger('owner_id')->nullable()->after('parent_id')->comment('Person responsible for the tab');
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('SET NULL');
        });
    }
};
