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
        Schema::create('system', function (Blueprint $table) {
            $table->id();
            $table->foreignId('server_device_id')->constrained('server_devices');
            $table->string('name');
            $table->string('description')->nullable();
            $table->foreignId('status_id')->constrained('option_lists');
            $table->foreignId('priority_id')->constrained('option_lists');
            $table->foreignId('area_id')->nullable()->constrained('option_lists');
            $table->foreignId('responsible_id')->nullable()->constrained('users');
            $table->string('api_doc_url')->nullable(); 
            $table->string('db_engine')->nullable();
            $table->string('db_name')->nullable();
            $table->string('db_host')->nullable();
            $table->integer('db_port')->nullable();
            $table->string('repository_url')->nullable();
            $table->timestamp('last_update')->nullable();
            $table->json('technical_notes')->nullable();
            $table->string('url')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system');
    }
};
