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
        Schema::create('domicilios', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->primary();
            $table->string('direccion',100);
            $table->string('telefono_fijo', 30)->nullable();
            $table->integer('departamento')->unsigned(false)->nullable();
            $table->integer('provincia')->unsigned(false)->nullable();
            $table->integer('distrito')->unsigned(false)->nullable();
            // FK
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('departamento')->references('id')->on('departamentos');
            $table->foreign('provincia')->references('id')->on('provincias');
            $table->foreign('distrito')->references('id')->on('distritos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domicilios');
    }
};
