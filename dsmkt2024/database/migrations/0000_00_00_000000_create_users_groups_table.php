<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users_groups', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name', 30)->nullable();
            $table->timestamps();
        });

        DB::table('users_groups')->insert([
            ['name' => 'Administrator'],
            ['name' => 'Koncesjoner'],
            ['name' => 'DS Polska'],
            ['name' => 'ADNP'],
            ['name' => 'ASO'],
            ['name' => 'ADNP+ASO'],
            ['name' => 'Agencja reklamowa'],
            ['name' => 'Pozostali'],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('users_groups');
    }
};
