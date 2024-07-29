<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BimestreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bimestres = [
            ['descripcion' => 'I Bimestre', 'esActivo' => true],
            ['descripcion' => 'II Bimestre', 'esActivo' => false],
            ['descripcion' => 'III Bimestre', 'esActivo' => false],
            ['descripcion' => 'IV Bimestre', 'esActivo' => false],
        ];

        DB::table('bimestres')->insert($bimestres);
    }
}
