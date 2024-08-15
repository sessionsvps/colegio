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
        Schema::create('director', function (Blueprint $table) {
            $table->string('codigo_director', 5)->unique();
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('primer_nombre', 30);
            $table->string('otros_nombres', 30)->nullable();
            $table->string('apellido_paterno', 30);
            $table->string('apellido_materno', 30);
            $table->datetime('fecha_nacimiento');
            $table->string('dni', 8)->unique();
            $table->string('email', 50)->unique();
            $table->boolean('sexo');
            $table->string('telefono_celular', 9)->nullable();
            $table->string('nacionalidad', 30);
            $table->integer('departamento')->unsigned(false)->nullable();
            $table->integer('provincia')->unsigned(false)->nullable();
            $table->integer('distrito')->unsigned(false)->nullable();
            $table->unsignedtinyInteger('id_estado');
            $table->datetime('fecha_ingreso');
            $table->timestamps();

            // Definir las claves foráneas
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_estado')->references('id_estado')->on('estados');
            
            $table->foreign('departamento')->references('id')->on('departamentos');
            $table->foreign('provincia')->references('id')->on('provincias');
            $table->foreign('distrito')->references('id')->on('distritos');

            // Definir clave primaria compuesta
            $table->primary(['codigo_director','user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('director');
    }
};
