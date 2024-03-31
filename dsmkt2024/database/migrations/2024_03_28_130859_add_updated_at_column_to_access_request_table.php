<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('access_requests', function (Blueprint $table) {
            $table->dateTime('updated_at')->nullable()->after('created_at');
        });
    }

    public function down()
    {
        Schema::table('access_requests', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
    }
};
