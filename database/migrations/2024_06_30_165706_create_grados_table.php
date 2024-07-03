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
        Schema::create('grados', function (Blueprint $table) {
            $table->tinyIncrements('id_grado');
            $table->unsignedTinyInteger('id_nivel');
            $table->string('detalle', 30);
            // FK
            $table->foreign('id_nivel')->references('id_nivel')->on('niveles');
            // Definir clave primaria compuesta
            $table->primary(['id_grado', 'id_nivel']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grados');
    }
};
