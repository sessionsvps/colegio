<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeccionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $secciones = [];
        $detalles = ['A', 'B', 'C', 'D'];

        // Asumiendo que id_nivel para Primaria es 1 y para Secundaria es 2
        for ($nivel = 1; $nivel <= 2; $nivel++) {
            $maxGrados = $nivel == 1 ? 6 : 5; // 6 grados en primaria, 5 grados en secundaria
            for ($grado = 1; $grado <= $maxGrados; $grado++) {
                foreach ($detalles as $detalle) {
                    $secciones[] = [
                        'id_grado' => $grado,
                        'id_nivel' => $nivel,
                        'detalle' => $detalle,
                        'esActivo' => true,
                    ];
                }
            }
        }

        DB::table('secciones')->insert($secciones);
    }
}
