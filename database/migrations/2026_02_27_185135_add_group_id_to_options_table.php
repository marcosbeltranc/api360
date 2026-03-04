<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('option_lists', function (Blueprint $table) {
            $table->foreignId('option_group_id')
                  ->after('id')
                  ->nullable() 
                  ->constrained('option_groups')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('option_lists', function (Blueprint $table) {
            $table->dropForeign(['option_group_id']);
            $table->dropColumn('option_group_id');
        });
    }
};
