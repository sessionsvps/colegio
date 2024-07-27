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
        Schema::create('asistencias', function (Blueprint $table) {
            $table->string('codigo_estudiante', 4);
            $table->integer('año_escolar');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('id_bimestre');
            $table->tinyInteger('inasistencias_justificadas');
            $table->tinyInteger('tardanzas_justificadas');
            $table->tinyInteger('tardanzas_injustificadas');
            $table->tinyInteger('inasistencias_injustificadas');
            // Definir la clave foránea
            $table->foreign('codigo_estudiante')->references('codigo_estudiante')->on('boleta_de_notas');
            $table->foreign('user_id')->references('user_id')->on('boleta_de_notas');
            $table->foreign('año_escolar')->references('año_escolar')->on('boleta_de_notas');
            $table->foreign('id_bimestre')->references('id')->on('bimestres');
            // Definir clave primaria compuesta
            $table->primary(['codigo_estudiante', 'año_escolar', 'user_id', 'id_bimestre']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistencias');
    }
};
