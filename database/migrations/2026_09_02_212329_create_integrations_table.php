<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('integrations', function (Blueprint $table) {
            $table->id();

            $table->string('name')->unique();
            $table->text('description');

            // Catálogos administrables desde option_lists
            $table->foreignId('integration_type_id')
                ->constrained('option_lists');

            $table->foreignId('criticality_id')
                ->constrained('option_lists');

            $table->foreignId('status_id')
                ->constrained('option_lists');

            $table->foreignId('authentication_method_id')
                ->constrained('option_lists');

            $table->foreignId('trigger_type_id')
                ->constrained('option_lists');

            // Sistemas participantes
            $table->foreignId('source_system_id')
                ->constrained('system');

            $table->foreignId('destination_system_id')
                ->constrained('system');

            $table->foreignId('responsible_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('external_support_contact')->nullable();
            $table->string('endpoint_url', 2048)->nullable();
            $table->string('frequency_detail')->nullable();
            $table->string('test_endpoint_url', 2048)->nullable();
            $table->string('repository_url', 2048)->nullable();
            $table->foreignId('server_device_id')->nullable()->constrained('server_devices')->nullOnDelete();
            $table->text('logs_location')->nullable();
            $table->text('alerts_channel')->nullable();
            $table->json('technical_notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['source_system_id', 'destination_system_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('integrations');
    }
};