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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id(); // id_notificacion
            $table->foreignId('container_id')->constrained('containers'); // id_contenedor
            $table->string('type', 50); // tipo_notificacion
            $table->text('description')->nullable(); // descripcion
            $table->timestamp('notification_date')->useCurrent(); // fecha_notificacion
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
