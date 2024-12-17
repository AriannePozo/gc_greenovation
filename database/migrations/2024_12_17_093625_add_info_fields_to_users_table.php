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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('user_type_id')->default(2)->constrained('user_types'); // id_tipo_usuario
            $table->foreignId('user_status_id')->default(1)->constrained('user_statuses'); // id_estado_usuario
            $table->string('ci', 20)->unique(); // ci
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
