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
        Schema::create('network_devices', function (Blueprint $table) {
$table->id();
            
            // --- Campos Obligatorios (Identificación y Relaciones) ---
            $table->string('name');
            $table->foreignId('status_id')->constrained('option_lists');
            $table->foreignId('device_type_id')->constrained('option_lists');
            $table->foreignId('location_id')->constrained('option_lists');

            // --- Campos Técnicos y Hardware (Todos Nullables) ---
            $table->string('sku')->nullable();
            $table->string('model')->nullable();
            $table->string('brand')->nullable();
            $table->string('serial_number')->nullable();
            $table->text('notes')->nullable();
            
            // Red
            $table->ipAddress('ip_address')->nullable();
            
            // Software y Fechas
            $table->date('last_maintenance')->nullable();
            $table->timestamp('last_update')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('network_devices');
    }
};
