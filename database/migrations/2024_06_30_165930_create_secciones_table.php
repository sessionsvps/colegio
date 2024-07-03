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
        Schema::create('secciones', function (Blueprint $table) {
            $table->tinyIncrements('id_seccion');
            $table->unsignedtinyInteger('id_grado');
            $table->unsignedtinyInteger('id_nivel');
            $table->string('detalle', 30);
            $table->boolean('esActivo');
            // FK
            $table->foreign('id_nivel')->references('id_nivel')->on('grados');
            $table->foreign('id_grado')->references('id_grado')->on('grados');
            // Definir clave primaria compuesta
            $table->primary(['id_seccion','id_grado', 'id_nivel']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('secciones');
    }
};
