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
        Schema::create('estudiantes', function (Blueprint $table) {
            $table->string('codigo_estudiante', 4)->unique();
            $table->unsignedBigInteger('user_id')->unique();
            $table->unsignedBigInteger('id_apoderado');
            $table->string('primer_nombre', 30);
            $table->string('otros_nombres', 30)->nullable();
            $table->string('apellido_paterno', 30);
            $table->string('apellido_materno', 30);
            $table->datetime('fecha_nacimiento');
            $table->string('dni', 8)->unique();
            $table->string('email', 50)->unique();
            $table->boolean('sexo');
            $table->string('telefono_celular', 9)->nullable();
            $table->string('nacionalidad', 50);
            $table->integer('departamento')->unsigned(false)->nullable();
            $table->integer('provincia')->unsigned(false)->nullable();
            $table->integer('distrito')->unsigned(false)->nullable();
            $table->string('nro_matricula', 10)->nullable()->unique();
            $table->integer('año_ingreso');
            $table->string('lengua_materna', 30);
            $table->string('colegio_procedencia', 50)->nullable();
            $table->timestamps();
            // Definir la clave foránea
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('id_apoderado')->references('id')->on('apoderados');

            $table->foreign('departamento')->references('id')->on('departamentos');
            $table->foreign('provincia')->references('id')->on('provincias');
            $table->foreign('distrito')->references('id')->on('distritos');
            // Definir clave primaria compuesta
            $table->primary(['codigo_estudiante', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estudiantes');
    }
};
