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
        Schema::create('server_access', function (Blueprint $table) {
            $table->id();
            $table->foreignId('server_device_id')->constrained('server_devices');
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('access_id')->nullable();
            $table->string('access_ip')->nullable();
            $table->text('password')->nullable();
            $table->integer('port')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('server_access');
    }
};
