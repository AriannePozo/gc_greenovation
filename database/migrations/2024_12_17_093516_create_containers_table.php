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
        Schema::create('containers', function (Blueprint $table) {
            $table->id(); // id_contenedor
            $table->string('name', 100); // nombre
            $table->decimal('latitude', 10, 8); // ubicacion_latitud
            $table->decimal('longitude', 11, 8); // ubicacion_longitud
            $table->timestamp('installation_date')->useCurrent(); // fecha_instalacion
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('containers');
    }
};
