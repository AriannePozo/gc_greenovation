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
        Schema::create('sensors', function (Blueprint $table) {
            $table->id(); // id_sensor
            $table->foreignId('sensor_type_id')->constrained('sensor_types'); // id_tipo_sensor
            $table->foreignId('container_id')->constrained('containers'); // id_contenedor
            $table->foreignId('sensor_status_id')->default(1)->constrained('sensor_statuses'); // id_estado
            $table->string('model', 50)->nullable(); // modelo
            $table->timestamp('installation_date')->useCurrent(); // fecha_instalacion
            $table->timestamp('last_reading')->nullable(); // ultima_lectura
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensors');
    }
};
