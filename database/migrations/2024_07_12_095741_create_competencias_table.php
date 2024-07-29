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
        Schema::create('competencias', function (Blueprint $table) {
            $table->string('codigo_curso',4);
            $table->tinyInteger('orden');
            $table->string('descripcion',300);
            // PK
            $table->primary(['codigo_curso', 'orden']);
            // FK
            $table->foreign('codigo_curso')->references('codigo_curso')->on('cursos');
            // Agregar índices
            $table->index('orden');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competencias');
    }
};
