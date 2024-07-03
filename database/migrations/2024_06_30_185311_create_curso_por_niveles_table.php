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
        Schema::create('curso_por_niveles', function (Blueprint $table) {
            $table->string('codigo_curso');
            $table->unsignedTinyInteger('id_nivel');
            // FK
            $table->foreign('codigo_curso')->references('codigo_curso')->on('cursos');
            $table->foreign('id_nivel')->references('id_nivel')->on('niveles');
            // Definir clave primaria compuesta
            $table->primary(['codigo_curso', 'id_nivel']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curso_por_niveles');
    }
};
