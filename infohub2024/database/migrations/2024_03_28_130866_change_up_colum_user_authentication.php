<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('user_authentication', function (Blueprint $table) {
            $table->ipAddress('ip')->change();
        });
    }

    public function down()
    {
        Schema::table('user_authentication', function (Blueprint $table) {
            $table->unsignedInteger('ip')->change();
        });
    }
};
