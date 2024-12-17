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
        Schema::create('sensor_types', function (Blueprint $table) {
            $table->id(); // id_tipo_sensor
            $table->string('name', 50)->unique(); // nombre_tipo
            $table->text('description')->nullable(); // descripcion
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensor_types');
    }
};
