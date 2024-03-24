<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('menu_items', function (Blueprint $table) {
            // Add the tree_id column for multi-tree support
            $table->unsignedInteger('tree_id')->nullable()->after('id')->comment('Tree identifier for multi-tree structures');

        });
    }

    public function down(): void {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropColumn('tree_id');
            
        });
    }
};
