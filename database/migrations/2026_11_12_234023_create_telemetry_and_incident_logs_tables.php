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
        Schema::create('telemetry_logs', function (Blueprint $table) {
            $table->id();
            $table->string('dispositivo', 64)->default('ESP32_SALA01');
            $table->string('sala_id', 64)->default('sala01')->index();
            $table->string('zona', 128)->nullable();
            $table->decimal('temperatura', 5, 2);
            $table->decimal('humedad', 5, 2);
            $table->decimal('sensacion_termica', 5, 2)->nullable();
            $table->unsignedInteger('gas')->default(0);
            $table->string('estado', 32)->default('NORMAL');
            $table->boolean('silenciado')->default(false);
            $table->unsignedBigInteger('uptime_s')->nullable();
            $table->timestamp('created_at')->useCurrent()->index();

            $table->index(['sala_id', 'created_at']);
            $table->index(['dispositivo', 'created_at']);
        });

        Schema::create('incident_logs', function (Blueprint $table) {
            $table->id();
            $table->string('dispositivo', 64)->default('ESP32_SALA01');
            $table->string('sala_id', 64)->default('sala01')->index();
            $table->string('zona', 128)->nullable();
            $table->string('sensor', 64);
            $table->string('valor', 64);
            $table->string('umbral', 64)->nullable();
            $table->string('severidad', 24)->default('warning')->index();
            $table->string('evento', 64)->nullable();
            $table->string('estado', 32)->default('Pendiente')->index();
            $table->string('reconocido_por', 128)->nullable();
            $table->timestamp('reconocido_at')->nullable();
            $table->timestamp('created_at')->useCurrent()->index();

            $table->index(['sala_id', 'severidad', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incident_logs');
        Schema::dropIfExists('telemetry_logs');
    }
};
