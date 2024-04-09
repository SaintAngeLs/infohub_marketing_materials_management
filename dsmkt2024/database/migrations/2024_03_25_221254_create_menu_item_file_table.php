<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('menu_id')->nullable()->index()->comment('jakiego samochodu dotyczy');
            $table->unsignedSmallInteger('auto_id')->nullable()->index()->comment('jakiego samochodu dotyczy');
            $table->unsignedBigInteger('add_by')->nullable()->comment('kto dodal plik');
            $table->unsignedBigInteger('update_by')->nullable()->comment('kto zaktualizaował plik');
            $table->smallInteger('display_order')->default(0);
            $table->string('name', 255)->nullable();
            $table->text('path')->nullable();
            $table->string('extension', 5)->nullable();
            $table->unsignedInteger('weight')->nullable();
            $table->unsignedTinyInteger('hosted')->default(0)->comment('0- lokalny serwer, 1- zewnetrzny serwer');
            $table->dateTime('start')->nullable();
            $table->dateTime('end')->nullable();
            $table->string('key_words', 255)->nullable()->comment('Słowa kluczowe – tagi');
            $table->tinyInteger('status')->default(0)->comment('0- inactive, 1- active');
            $table->tinyInteger('archived')->default(0)->comment('1 archived');
            $table->dateTime('archived_at')->nullable()->comment('data przeniesienia do archiwum');
            $table->unsignedBigInteger('archived_by')->nullable()->comment('kto przeniosl');
            $table->timestamps();
            $table->timestamp('fingerprint')->useCurrent();

            // Foreign keys definitions
            $table->foreign('menu_id')->references('id')->on('menu_items')->onDelete('SET NULL');
            $table->foreign('auto_id')->references('id')->on('autos')->onDelete('SET NULL');
            $table->foreign('add_by')->references('id')->on('users')->onDelete('SET NULL');
            $table->foreign('update_by')->references('id')->on('users')->onDelete('SET NULL');
            $table->foreign('archived_by')->references('id')->on('users')->onDelete('SET NULL');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
