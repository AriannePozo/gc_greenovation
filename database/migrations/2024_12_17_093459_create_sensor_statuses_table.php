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
        Schema::create('sensor_statuses', function (Blueprint $table) {
            $table->id(); // id_estado
            $table->string('name', 50)->unique(); // nombre_estado
            $table->text('description')->nullable(); // descripcion
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensor_statuses');
    }
};
