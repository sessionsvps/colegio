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
        Schema::create('instituciones', function (Blueprint $table) {
            $table->string('codigo_modular',10)->primary();
            $table->string('nombre',100);
            $table->string('direccion', 100);
            $table->string('ugel', 50);
            $table->string('nombre_director', 100);
            $table->string('apellido_director', 100);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instituciones');
    }
};
