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
        Schema::create('estudiante_secciones', function (Blueprint $table) {
            $table->string('codigo_estudiante', 4);
            $table->unsignedBigInteger('user_id');
            $table->integer('año_escolar');
            $table->unsignedTinyInteger('id_seccion');
            $table->unsignedTinyInteger('id_nivel');
            $table->unsignedTinyInteger('id_grado');
            $table->tinyInteger('esActivo');

            // Definir la clave foránea
            $table->foreign('id_seccion')->references('id_seccion')->on('secciones');
            $table->foreign('id_nivel')->references('id_nivel')->on('secciones');
            $table->foreign('id_grado')->references('id_grado')->on('secciones');
            $table->foreign('codigo_estudiante')->references('codigo_estudiante')->on('estudiantes');
            $table->foreign('user_id')->references('user_id')->on('estudiantes');
            // Definir clave primaria compuesta
            $table->primary(['codigo_estudiante', 'user_id', 'año_escolar']);
            // Agregar índices
            $table->index('año_escolar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estudiante_secciones');
    }
};
