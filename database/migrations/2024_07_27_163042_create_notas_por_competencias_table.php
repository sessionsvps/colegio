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
        Schema::create('notas_por_competencias', function (Blueprint $table) {
            $table->string('codigo_estudiante', 4);
            $table->integer('año_escolar');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('id_bimestre');
            $table->string('codigo_curso', 4);
            $table->tinyInteger('orden');
            $table->string('nivel_logro',2)->nullable();
            //$table->string('conclusion',50)->nullable();
            $table->boolean('exoneracion');
            // Definir la clave foránea
            $table->foreign('codigo_estudiante')->references('codigo_estudiante')->on('boleta_de_notas');
            $table->foreign('user_id')->references('user_id')->on('boleta_de_notas');
            $table->foreign('año_escolar')->references('año_escolar')->on('boleta_de_notas');
            $table->foreign('id_bimestre')->references('id')->on('bimestres');
            $table->foreign('codigo_curso')->references('codigo_curso')->on('competencias');
            $table->foreign('orden')->references('orden')->on('competencias');
            // Definir clave primaria compuesta
            $table->primary(['codigo_estudiante', 'año_escolar', 'user_id', 'id_bimestre', 'codigo_curso', 'orden']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notas_por_competencias');
    }
};
