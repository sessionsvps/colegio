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
        Schema::create('catedras', function (Blueprint $table) {
            $table->string('codigo_curso',4);
            $table->unsignedTinyInteger('id_seccion');
            $table->unsignedTinyInteger('id_nivel');
            $table->unsignedTinyInteger('id_grado');
            $table->string('codigo_docente', 4);
            $table->integer('año_escolar');
            $table->unsignedBigInteger('user_id');

            // Definir la clave foránea
            $table->foreign('codigo_curso')->references('codigo_curso')->on('cursos');
            $table->foreign('id_seccion')->references('id_seccion')->on('secciones');
            $table->foreign('id_nivel')->references('id_nivel')->on('secciones');
            $table->foreign('id_grado')->references('id_grado')->on('secciones');
            $table->foreign('codigo_docente')->references('codigo_docente')->on('docentes');
            $table->foreign('user_id')->references('user_id')->on('docentes');
            // Definir clave primaria compuesta
            $table->primary(['codigo_curso', 'id_seccion', 'id_nivel', 'id_grado', 'año_escolar']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catedras');
    }
};
