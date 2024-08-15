<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $path = database_path('sql_files/ubigeo_peru.sql');
        DB::unprepared(file_get_contents($path));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departamentos');
        Schema::dropIfExists('provincias');
        Schema::dropIfExists('distritos');
    }
};
