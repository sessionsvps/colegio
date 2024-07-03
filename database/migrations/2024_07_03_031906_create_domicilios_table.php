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
            $table->string('departamento', 30);
            $table->string('provincia', 30);
            $table->string('distrito', 30);
            // FK
            $table->foreign('user_id')->references('id')->on('users');
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
