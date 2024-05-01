<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('user_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('uri', 100)->nullable();
            $table->text('post_string')->nullable();
            $table->text('query_string')->nullable();
            $table->text('file_string')->nullable();
            $table->ipAddress('ip')->nullable();
            $table->timestamp('fingerprint')->useCurrent();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_logs');
    }
};
