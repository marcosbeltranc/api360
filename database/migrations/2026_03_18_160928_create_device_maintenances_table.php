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
        Schema::create('device_maintenances', function (Blueprint $table) {
            $table->id();
            $table->morphs('device'); 
            $table->foreignId('maintenance_type_id')->constrained('option_lists');
            $table->string('title'); 
            $table->foreignId('responsible_id')->constrained('users');
            $table->date('completion_date');
            $table->text('details')->nullable();
            $table->json('validation_checklist')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_maintenances');
    }
};
