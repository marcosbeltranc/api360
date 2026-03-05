<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_area', function (Blueprint $table) {

            $table->id();

            $table->foreignId('system_id')
                ->constrained('system')
                ->cascadeOnDelete();

            $table->foreignId('area_id')
                ->constrained('option_lists')
                ->cascadeOnDelete();

            // evita duplicados
            $table->unique(['system_id', 'area_id']);

            // índices para queries rápidas
            $table->index('system_id');
            $table->index('area_id');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_area');
    }
};