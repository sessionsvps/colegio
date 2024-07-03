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
        Schema::create('exoneraciones', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->string('codigo_estudiante', 4);
            $table->string('codigo_curso', 4);
            $table->integer('año_escolar');
            
            // Definir la clave foránea
            $table->foreign('codigo_estudiante')->references('codigo_estudiante')->on('estudiantes');
            $table->foreign('user_id')->references('user_id')->on('estudiantes');
            $table->foreign('codigo_curso')->references('codigo_curso')->on('cursos');
            // Definir clave primaria compuesta
            $table->primary(['codigo_estudiante', 'codigo_curso','año_escolar','user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exoneraciones');
    }
};
