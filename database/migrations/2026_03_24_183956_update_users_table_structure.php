<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'level',
                'created_at',
                'updated_at',
                'deleted_at'
            ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('level')
                ->default(4)
                ->comment('0:admin, 1:developer, 2:network, 3:support, 4:user');

            $table->string('label')->nullable();

            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'level',
                'label',
                'created_at',
                'updated_at',
                'deleted_at'
            ]);
        });

        Schema::table('users', function (Blueprint $table) {
            // Restaurar estructura anterior
            $table->tinyInteger('level')
                ->default(2)
                ->after('password')
                ->comment('0:admin, 1:developer, 2:user');

            $table->timestamps();
            $table->softDeletes();
        });
    }
};