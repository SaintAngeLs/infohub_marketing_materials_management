<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('files', function (Blueprint $table) {
            // Add the file_source column as a string and make it nullable
            $table->string('file_source')->nullable()->after('auto_id')->comment('Source of the file');
        });
    }

    public function down(): void
    {
        Schema::table('files', function (Blueprint $table) {
            // Remove the file_source column if the migration is rolled back
            $table->dropColumn('file_source');
        });
    }
};
    