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
            ['id_nivel' => 1, 'detalle' => '1ro'],
            ['id_nivel' => 1, 'detalle' => '2do'],
            ['id_nivel' => 1, 'detalle' => '3ro'],
            ['id_nivel' => 1, 'detalle' => '4to'],
            ['id_nivel' => 1, 'detalle' => '5to'],
            ['id_nivel' => 1, 'detalle' => '6to'],
            ['id_nivel' => 2, 'detalle' => '1ro'],
            ['id_nivel' => 2, 'detalle' => '2do'],
            ['id_nivel' => 2, 'detalle' => '3ro'],
            ['id_nivel' => 2, 'detalle' => '4to'],
            ['id_nivel' => 2, 'detalle' => '5to'],
        ];

        DB::table('grados')->insert($grados);
    }
}
