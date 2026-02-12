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
        Schema::create('infrastructure_devices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            
            // Relaciones con option_lists
            $table->foreignId('device_type_id')->constrained('option_lists'); // Servidor, NAS, Red
            $table->foreignId('sub_type_id')->nullable()->constrained('option_lists'); // Físico/Virtual o Switch/Router
            $table->foreignId('location_id')->constrained('option_lists');
            $table->foreignId('status_id')->constrained('option_lists');

            // Especificaciones Técnicas (Nulables según el tipo)
            $table->string('cpu')->nullable();     // Servidores
            $table->string('ram')->nullable();     // Servidores
            $table->string('storage')->nullable(); // Servidores / NAS
            $table->string('ip_address')->nullable();
            $table->string('raid_type')->nullable(); // NAS
            $table->integer('folders_count')->nullable(); // NAS

            // Hardware y Mantenimiento
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->date('last_maintenance')->nullable();
            $table->date('next_maintenance')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('infrastructure_devices');
    }
};
