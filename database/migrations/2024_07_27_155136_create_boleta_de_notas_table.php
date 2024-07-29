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
        Schema::create('boleta_de_notas', function (Blueprint $table) {
            $table->string('codigo_estudiante', 4);
            $table->integer('año_escolar');
            $table->unsignedBigInteger('user_id');
            $table->string('codigo_modular', 10);
            // Definir la clave foránea
            $table->foreign('codigo_estudiante')->references('codigo_estudiante')->on('estudiantes');
            $table->foreign('user_id')->references('user_id')->on('estudiantes');
            $table->foreign('codigo_modular')->references('codigo_modular')->on('instituciones');
            // Definir clave primaria compuesta
            $table->primary(['codigo_estudiante', 'año_escolar', 'user_id']);
            // Agregar índices
            $table->index('año_escolar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boleta_de_notas');
    }
};
