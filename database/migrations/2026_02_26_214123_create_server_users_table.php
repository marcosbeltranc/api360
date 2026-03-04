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
        Schema::create('server_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('server_device_id')->constrained('server_devices');
            $table->string('name');
            $table->text('password')->nullable();
            $table->string('description')->nullable();
            $table->string('type');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('server_users');
    }
};
