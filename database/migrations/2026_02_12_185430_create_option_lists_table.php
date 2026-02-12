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
        Schema::create('option_lists', function (Blueprint $table) {
            $table->id();
            $table->string('name');          // Ej: "Físico", "Data Center Principal"
            $table->string('type');          // Ej: "server_type", "location"
            $table->string('slug');          // Ej: "fisico", "dc_principal"
            $table->string('color')->nullable(); // Para los badges (success, error, #hex)
            $table->integer('sort_order')->default(0); 
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();           // Importante para no romper relaciones históricas

            // Índice para que el API responda rápido al filtrar por tipo
            $table->index(['type', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('option_lists');
    }
};
