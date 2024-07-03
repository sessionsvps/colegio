<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NivelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $niveles = [
            ['detalle' => 'Primaria'],
            ['detalle' => 'Secundaria'],
        ];

        DB::table('niveles')->insert($niveles);
    }
}
