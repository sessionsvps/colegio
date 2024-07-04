<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GradoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $grados = [
            ['id_grado' => 1, 'id_nivel' => 1, 'detalle' => '1ro'],
            ['id_grado' => 2, 'id_nivel' => 1, 'detalle' => '2do'],
            ['id_grado' => 3, 'id_nivel' => 1, 'detalle' => '3ro'],
            ['id_grado' => 4, 'id_nivel' => 1, 'detalle' => '4to'],
            ['id_grado' => 5, 'id_nivel' => 1, 'detalle' => '5to'],
            ['id_grado' => 6, 'id_nivel' => 1, 'detalle' => '6to'],
            ['id_grado' => 1, 'id_nivel' => 2, 'detalle' => '1ro'],
            ['id_grado' => 2, 'id_nivel' => 2, 'detalle' => '2do'],
            ['id_grado' => 3, 'id_nivel' => 2, 'detalle' => '3ro'],
            ['id_grado' => 4, 'id_nivel' => 2, 'detalle' => '4to'],
            ['id_grado' => 5, 'id_nivel' => 2, 'detalle' => '5to'],
        ];

        DB::table('grados')->insert($grados);
    }
}
