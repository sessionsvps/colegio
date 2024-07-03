<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $estados = [
            ['detalle' => 'Soltero'],
            ['detalle' => 'Casado'],
            ['detalle' => 'Viudo'],
            ['detalle' => 'Divorciado'],
        ];

        DB::table('estados')->insert($estados);
    }
}
