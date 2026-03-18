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
        Schema::table('server_devices', function (Blueprint $table) {
            $table->dropColumn(['created_at', 'updated_at', 'deleted_at']);
        });

        Schema::table('server_devices', function (Blueprint $table) {
            $table->text('maintenance_notes')->nullable()->after('os');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('server_devices', function (Blueprint $table) {
            $table->dropColumn(['maintenance_notes', 'created_at', 'updated_at', 'deleted_at']);
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
