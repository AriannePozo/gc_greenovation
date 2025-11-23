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
        Schema::create('readings', function (Blueprint $table) {
            $table->id(); // id_lectura
            $table->foreignId('sensor_id')->constrained('sensors'); // id_sensor
            $table->foreignId('container_id')->constrained('containers'); // id_contenedor
            $table->float('value'); // valor
            $table->timestamp('reading_date')->useCurrent(); // fecha_lectura
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('readings');
    }
};
